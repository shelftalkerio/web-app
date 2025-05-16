<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Redirect;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Facades\Filament;

class Register extends BaseRegister
{
    public function register(): ?RegistrationResponse
    {
        $this->validate();

        $data = $this->data;

        unset($data['passwordConfirmation']);

        User::create($data);

        Redirect::to(Filament::getLoginUrl());

        return null;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getCompanyFormComponent(),
                        $this->getRoleFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getCompanyFormComponent(): Component
    {
        return Select::make('company_id')
            ->label('Company')
            ->options(
                Company::query()
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray()
            )
            ->searchable()
            ->required();
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->options(
                collect(UserRole::cases())
                    ->mapWithKeys(fn ($role) => [$role->value => $role->getLabel()])
                    ->toArray()
            )
            ->enum(UserRole::class)
            ->default(UserRole::Store)
            ->required();
    }
}
