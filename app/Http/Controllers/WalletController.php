<?php
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
                'montanEnvoyer' => 'required|numeric|min:1',
                'montanRecive'=>'required|numeric|min:1'
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
    
    
            if ($request->montanEnvoyer > $wallet->balance) {
                return response()->json([
                    'message' => 'Le montant de votre virement dépasse le solde de votre compte'
                ], 400);
            }
    
    
            if ($wallet->status == false) {
                return response()->json([
                    'message' => 'Votre carte est bloquée. Veuillez contacter l\'assistance au 0609117392'
                ], 403);
            }
    
    
            $wallet->balance -= $request->montanEnvoyer;
            $wallet->save();
    
            return $wallet;
            
            $Wallet = Wallet::where('email', $request->id)->first();
            if ($Wallet) {
                $Wallet->balance += $request->montanEnvoyer;
                $Wallet->save();
            }
    
            return response()->json([
                'message' => 'Solde mis à jour avec succès',
                'wallet' => $wallet
            ]);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
