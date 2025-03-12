?<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;
use Exception;

class WalletController extends Controller
{
    public function updateSold(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'balance' => 'required|numeric',
                'montantEnvoyer' => 'required|numeric|min:1',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Utilisateur non trouvé avec cet email'
                ], 404);
            }

            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return response()->json([
                    'message' => 'Aucun portefeuille trouvé pour cet utilisateur'
                ], 404);
            }

            if ($request->montantEnvoyer > $wallet->balance) {
                return response()->json([
                    'message' => 'Le montant de votre virement dépasse le solde de votre compte'
                ], 400);
            }

            if ($wallet->status == false) {
                return response()->json([
                    'message' => 'Votre carte est bloquée. Veuillez contacter l\'assistance au 0609117392'
                ], 403);
            }

            
            $wallet->balance -= $request->montantEnvoyer;
            $wallet->save();

            
            $walletReceiver = Wallet::where('user_id', $request->email)->first();
            if ($walletReceiver) {
                $walletReceiver->balance += $request->montantEnvoyer;
                $walletReceiver->save();
            } else {
                return response()->json([
                    'message' => 'Aucun portefeuille trouvé pour le destinataire'
                ], 404);
            }

            return response()->json([
                'message' => 'Solde mis à jour avec succès',
                'wallet' => $wallet,
                'walletReceiver' => $walletReceiver
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
