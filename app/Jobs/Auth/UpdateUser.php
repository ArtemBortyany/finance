<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Events\Auth\UserUpdated;
use App\Events\Auth\UserUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Auth\User;
use App\Models\Common\Company;
use Illuminate\Support\Facades\Artisan;

class UpdateUser extends Job implements ShouldUpdate
{
    public function handle(): User
    {
        $this->authorize();

        // Do not reset password if not entered/changed
        if (empty($this->request['password'])) {
            unset($this->request['current_password']);
            unset($this->request['password']);
            unset($this->request['password_confirmation']);
        }

        event(new UserUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $this->model->update($this->request->input());

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'users');

                $this->model->attachMedia($media, 'picture');
            }

            if ($this->request->has('roles')) {
                $this->model->roles()->sync($this->request->get('roles'));
            }

            if ($this->request->has('companies')) {
                if (app()->runningInConsole() || request()->isInstall()) {
                    $sync = $this->model->companies()->sync($this->request->get('companies'));
                } else {
                    $user = user();

                    $companies = $user->withoutEvents(function () use ($user) {
                        return $user->companies()->whereIn('id', $this->request->get('companies'))->pluck('id');
                    });

                    if ($companies->isNotEmpty()) {
                        $sync = $this->model->companies()->sync($companies->toArray());
                    }
                }
            }

            if ($this->model->contact) {
                $this->model->contact->update($this->request->input());
            }

            if (isset($sync) && !empty($sync['attached'])) {
                foreach ($sync['attached'] as $id) {
                    $company = Company::find($id);

                    Artisan::call('user:seed', [
                        'user' => $this->model->id,
                        'company' => $company->id,
                    ]);

                    $this->dispatch(new CreateInvitation($this->model, $company));
                }
            }

            if (isset($sync) && !empty($sync['detached'])) {
                foreach ($sync['detached'] as $id) {
                    $company = Company::find($id);

                    if ($this->model->hasPendingInvitation($company->id)) {
                        $pending_invitation = $this->model->getPendingInvitation($company->id);

                        $this->dispatch(new DeleteInvitation($pending_invitation));
                    }
                }
            }
        });

        event(new UserUpdated($this->model, $this->request));

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't disable yourself
        if (($this->request->get('enabled', 1) == 0) && ($this->model->id == user()->id)) {
            $message = trans('auth.error.self_disable');

            throw new \Exception($message);
        }
    }
}
