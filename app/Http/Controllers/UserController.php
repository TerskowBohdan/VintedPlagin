<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class UserController extends Controller
{
    public function user(Request $request)
    {
        $userid = $request->userid;
        $date = $request->date;
        $spent_republics = $request->republics;

        $user = Users::where("userid", $userid)->value("id");
        if($user !== null)
        {
           $time = Users::where("userid", $userid)->value("date");
           if($spent_republics !== "none")
           {
                $republics = Users::where("userid", $userid)->value("republics") - $spent_republics;
                Users::where("userid", $userid)->update([
                    "republics" => ($republics > 0) ? $republics : 0
                ]);
                return response()->json(["date" => $time, "republics" => $republics]);
           }
           else
           {
                $republics = Users::where("userid", $userid)->value("republics");
                return response()->json(["date" => $time, "republics" => $republics]);
           }
        }

        else
        {
            Users::create([
                "userid" => $userid,
                "date" => $date,
                "republics" => 300
            ]);

            return response()->json(["date" => 0, "republics" => 300]);
        }
    }

    public function payment()
    {
        $stripe = new \Stripe\StripeClient(
          'sk_test_51MyDldHmbPsHrqxdFdZzC9528gjB309mqflGHH6JVw1sIRcQdcTr2woGUJFchqDD9cBdXMdpIPex8Lqxq2u1qdU4002mZXX5pd'
        );

        $session = $stripe->checkout->sessions->create([
          'payment_method_types' => ['card'],
          'line_items' => [[
            'price_data' => [
              'currency' => 'usd',
              'product_data' => [
                'name' => 'Vinted_first',
              ],
              'unit_amount' => 30000, 
            ],
            'quantity' => 1,
          ]],
          'mode' => 'payment',
          'success_url' => 'https://www.vinted.fr/',
          'cancel_url' => 'https://example.com/cancel',
        ]);

        return view('welcome', ['sessionId' => $session->id]);
    }
}
