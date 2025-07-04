<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
 

class Register extends BaseRegister 
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text'; 

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
                        $this->getRoleFormComponent(), 
                    ])
                    ->statePath('data'),
            ),
        ];
    }
 
    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->options([
                'super admin' => 'Super Admin',
                'admin' => 'Admin',
            ])
            ->default('admin')
            ->required();
    }

    
}
