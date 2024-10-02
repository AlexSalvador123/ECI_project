<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Price;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class PriceController extends Controller
{
    function importPrices() {
        $p_id=DB::table('products')
            ->select('*')
            ->get();

        $sku_ids=[];
        foreach ($p_id as $elem) {
            $sku_ids[$elem->sku]=$elem->id;
        };

        $a_id=DB::table('accounts')
                ->select('*')
                ->get();

        $acc_ids=[];
            foreach ($a_id as $elem) {
                $acc_ids[$elem->external_reference]=$elem->id;
            };


        if (($handle = fopen(base_path('import.csv'), "r")) !== FALSE) {
            $firstLine = true;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($firstLine) {
                    $firstLine = false;
                    continue;
                }
                $sku = $sku_ids[$data[0]];
                $account_ref = $data[1]?$acc_ids[$data[1]]:null;
                $user_ref = $data[2] === null?$data[2]:null;
                $quantity = $data[3];
                $price = $data[4];

                DB::table('prices')->updateOrInsert(
                    [
                        'product_id' => $sku,
                        'account_id' => $account_ref,
                        'user_id'    => $user_ref,
                    ],
                    [
                        'quantity' => $quantity,
                        'value'    => $price,
                    ]
                );
            }
        }

    }

    function getLivePrices() {
        $json = file_get_contents(base_path('live_prices.json'));
        return json_decode($json, true);
    }


    function getProductPrice(Request $request) {

        $productCodes = $request->input('productCodes',[]);
        $accountId = $request->input('accountId', null);

        if (count($productCodes)==0){
            return null;
        }
        $products=array();
        foreach ($productCodes as $p) {
            if($p!=''){
                $products[$p]=INF;
            }
        }
        if (count($products)==0){
            return null;
        }
        $livePrices = $this->getLivePrices();
        foreach ($livePrices as $livePrice) {
            foreach ($products as $product_code => $product_value) {
                if ($livePrice['sku'] == $product_code){
                    if(array_key_exists('account',$livePrice)){
                        if($livePrice['account'] == $accountId){
                            if($product_value>$livePrice['price']){
                                $products[$product_code]=$livePrice['price'];
                            }
                        }
                    }else{
                        if($product_value>$livePrice['price']){
                            $products[$product_code]=$livePrice['price'];
                        }
                    }
                }
            }
        }
        foreach ($products as $product_code => $product_value) {

            if($product_value == INF){

                $query = DB::table('prices')
                    ->leftjoin('products','products.id','prices.product_id')
                    ->leftjoin('accounts','accounts.id','prices.account_id')
                    ->select('value')
                    ->where('products.sku', $product_code);
                if ($accountId !== null) {
                    $query->where(function ($q) use ($accountId) {
                        $q->where('accounts.external_reference', $accountId)
                        ->orWhereNull('prices.account_id');
                    })
                    ->orderBy('value', 'asc')
                    ->limit(1);
                } else {
                    $query->whereNull('prices.account_id')
                        ->orderBy('value', 'asc')
                        ->limit(1);
                }


                $result = $query->first();

                if ($result) {
                    $products[$product_code]=$result->value;
                    //return ['product_code' => $product_code, 'price' => $result->value];
                }else{
                    $products[$product_code]=null;
                    //return ['product_code' => $product_code, 'price' => null];
                }
            }
        }

        return ($products);
    }


}
