<?php

namespace App\Filament\Clusters\Duekku\Resources\TransactionResource\Pages;

use App\Filament\Clusters\Duekku\Resources\TransactionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    // before creating a transaction, set the user_id to the authenticated user's ID
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        $account = \App\Models\Account::find($data['account_id']);

        // If the transaction type is 'expense', decrement the account's initial balance
        if ($data['type'] === 'expense') {
            if ($account) {
                $account->decrement('initial_balance', $data['amount']);
            }
        } elseif ($data['type'] === 'income') {
            // If the transaction type is 'income', increment the account's initial balance
            if ($account) {
                $account->increment('initial_balance', $data['amount']);
            }
        } elseif ($data['type'] === 'transfer') {
            if ($account) {
                // For transfer, we need to handle both accounts
                $accountTo = \App\Models\Account::find($data['account_id_to']);
                if ($accountTo) {
                    // Decrement from the source account
                    $account->decrement('initial_balance', $data['amount']);
                    // Increment to the destination account
                    $accountTo->increment('initial_balance', $data['amount']);
                }
            }
        }
        return $data;
    }
}
