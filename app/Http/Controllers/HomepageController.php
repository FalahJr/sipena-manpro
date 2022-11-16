<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Account;

use App\Authentication;

use Auth;

use Carbon\Carbon;

use Session;

use DB;

use Response;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      $produk = DB::table("produk")
                  ->get();

      foreach ($produk as $key => $value) {
        $avgdata = DB::table("transaction_detail")
                    ->join('feedback', 'feedback.id_transaction','transaction_detail.id_transaction')
                    ->join("account", 'account.id_account', 'feedback.id_user')
                    ->where("transaction_detail.id_produk", $value->id_produk)
                    ->groupBy('feedback.id_feedback')
                    ->select('transaction_detail.id_produk','transaction_detail.price','feedback.id_feedback','feedback.id_user','feedback.id_toko','feedback.star','feedback.image','feedback.feedback','feedback.created_at','account.id_account','account.fullname','account.email')
                    // ->having('feedback.created_at')
                    // ->avg('feedback.star');
                    ->get();
                  // dd($avgfeed);

        $avgfeed = 0;
        foreach ($avgdata as $key1 => $value1) {
          $avgfeed += $value1->star;
        }

        if ($avgfeed != 0)   {
          $avgfeed = $avgfeed / count($avgdata);

          DB::table("produk")
            ->where("id_produk", $value->id_produk)
            ->update([
              'star' => $avgfeed
            ]);
        }
      }

        $backgroundheader = DB::table("backgroundheader")->where("id", 1)->first();

        if (Auth::check()) {
          $latest = DB::table("produk")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'produk.id_produk')
                      ->join("account", 'produk.id_account', 'account.id_account')
                      ->latest('produk.created_at')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->where("account.id_account", '!=', Auth::user()->id_account)
                      ->groupby("imageproduk.id_produk")
                      ->select('produk.*', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(20)
                      ->get();

          $promo = DB::table("produk")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'produk.id_produk')
                      ->join("account", 'produk.id_account', 'account.id_account')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->where("produk.isdiskon", 'Y')
                      ->where("account.id_account", '!=', Auth::user()->id_account)
                      ->groupby("imageproduk.id_produk")
                      ->orderby('produk.sold', 'DESC')
                      ->select('produk.*', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(10)
                      ->get();

          $forauction = DB::table("lelang")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'lelang.id_produk')
                      ->join("account", 'lelang.id_account', 'account.id_account')
                      ->join('produk', 'produk.id_produk', '=', 'lelang.id_produk')
                      ->latest('lelang.created_at')
                      ->where("isactive", 'Y')
                      ->where("iswon", 'N')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->where("account.id_account", '!=', Auth::user()->id_account)
                      ->groupby("imageproduk.id_produk")
                      ->select("lelang.*", 'produk.name', 'produk.price as produkprice', 'produk.isdiskon', 'produk.star as starproduk', 'produk.diskon', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(20)
                      ->get();
        } else {
          $latest = DB::table("produk")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'produk.id_produk')
                      ->join("account", 'produk.id_account', 'account.id_account')
                      ->latest('produk.created_at')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->groupby("imageproduk.id_produk")
                      ->limit(20)
                      ->select('produk.*', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->get();

          $promo = DB::table("produk")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'produk.id_produk')
                      ->join("account", 'produk.id_account', 'account.id_account')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->where("produk.isdiskon", 'Y')
                      ->groupby("imageproduk.id_produk")
                      ->orderby('produk.sold', 'DESC')
                      ->select('produk.*', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(10)
                      ->get();

          $forauction = DB::table("lelang")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'lelang.id_produk')
                      ->join("account", 'lelang.id_account', 'account.id_account')
                      ->join('produk', 'produk.id_produk', '=', 'lelang.id_produk')
                      ->latest('lelang.created_at')
                      ->where("isactive", 'Y')
                      ->where("iswon", 'N')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->groupby("imageproduk.id_produk")
                      ->select("lelang.*", 'produk.name', 'produk.price as produkprice', 'produk.isdiskon', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(20)
                      ->get();
        }

        foreach ($forauction as $key => $value) {
            $bid = DB::table("lelangbid")
                    ->where("id_lelang", $value->id_lelang)
                    ->max('price');

            if ($bid != null) {
              $forauction[$key]->price = $bid;
            }
        }
        // dd($forauction);
        return view("homepage", compact('backgroundheader', 'forauction', 'latest', 'promo'));
    }

    public function apihomepage(Request $req) {
      $produk = DB::table("produk")
                  ->get();

      foreach ($produk as $key => $value) {
        $avgdata = DB::table("transaction_detail")
                    ->join('feedback', 'feedback.id_transaction','transaction_detail.id_transaction')
                    ->join("account", 'account.id_account', 'feedback.id_user')
                    ->where("transaction_detail.id_produk", $value->id_produk)
                    ->groupBy('feedback.id_feedback')
                    ->select('transaction_detail.id_produk','transaction_detail.price','feedback.id_feedback','feedback.id_user','feedback.id_toko','feedback.star','feedback.image','feedback.feedback','feedback.created_at','account.id_account','account.fullname','account.email')
                    // ->having('feedback.created_at')
                    // ->avg('feedback.star');
                    ->get();
                  // dd($avgfeed);

        $avgfeed = 0;
        foreach ($avgdata as $key1 => $value1) {
          $avgfeed += $value1->star;
        }

        if ($avgfeed != 0)   {
          $avgfeed = $avgfeed / count($avgdata);

          DB::table("produk")
            ->where("id_produk", $value->id_produk)
            ->update([
              'star' => $avgfeed
            ]);
        }
      }

        $backgroundheader = DB::table("backgroundheader")->where("id", 1)->first();

        if (Auth::check()) {
          $latest = DB::table("produk")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'produk.id_produk')
                      ->join("account", 'produk.id_account', 'account.id_account')
                      ->latest('produk.created_at')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->where("account.id_account", '!=', $req->id_account)
                      ->groupby("imageproduk.id_produk")
                      ->select('produk.*', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(20)
                      ->get();

          $promo = DB::table("produk")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'produk.id_produk')
                      ->join("account", 'produk.id_account', 'account.id_account')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->where("produk.isdiskon", 'Y')
                      ->where("account.id_account", '!=', $req->id_account)
                      ->groupby("imageproduk.id_produk")
                      ->orderby('produk.sold', 'DESC')
                      ->select('produk.*', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(10)
                      ->get();

          $forauction = DB::table("lelang")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'lelang.id_produk')
                      ->join("account", 'lelang.id_account', 'account.id_account')
                      ->join('produk', 'produk.id_produk', '=', 'lelang.id_produk')
                      ->latest('lelang.created_at')
                      ->where("isactive", 'Y')
                      ->where("iswon", 'N')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->where("account.id_account", '!=', $req->id_account)
                      ->groupby("imageproduk.id_produk")
                      ->select("lelang.*", 'produk.name', 'produk.price as produkprice', 'produk.isdiskon', 'produk.star as starproduk', 'produk.diskon', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(20)
                      ->get();
        } else {
          $latest = DB::table("produk")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'produk.id_produk')
                      ->join("account", 'produk.id_account', 'account.id_account')
                      ->latest('produk.created_at')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->groupby("imageproduk.id_produk")
                      ->limit(20)
                      ->select('produk.*', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->get();

          $promo = DB::table("produk")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'produk.id_produk')
                      ->join("account", 'produk.id_account', 'account.id_account')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->where("produk.isdiskon", 'Y')
                      ->groupby("imageproduk.id_produk")
                      ->orderby('produk.sold', 'DESC')
                      ->select('produk.*', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(10)
                      ->get();

          $forauction = DB::table("lelang")
                      ->join('imageproduk', 'imageproduk.id_produk', '=', 'lelang.id_produk')
                      ->join("account", 'lelang.id_account', 'account.id_account')
                      ->join('produk', 'produk.id_produk', '=', 'lelang.id_produk')
                      ->latest('lelang.created_at')
                      ->where("isactive", 'Y')
                      ->where("iswon", 'N')
                      ->where("account.istoko", 'Y')
                      ->where("produk.stock", '>' , 0)
                      ->groupby("imageproduk.id_produk")
                      ->select("lelang.*", 'produk.name', 'produk.price as produkprice', 'produk.isdiskon', 'produk.star as starproduk', 'produk.url_segment', 'account.*', 'imageproduk.*')
                      ->limit(20)
                      ->get();
        }

        foreach ($forauction as $key => $value) {
            $bid = DB::table("lelangbid")
                    ->where("id_lelang", $value->id_lelang)
                    ->max('price');

            if ($bid != null) {
              $forauction[$key]->price = $bid;
            }
        }

        return response()->json([
          'code' => 200,
          'message' => "sukses",
          'backgroundheader' => $backgroundheader,
          'forauction' => $forauction,
          'latest' => $latest,
          'promo' => $promo,
        ]);
    }

    public function getinfo() {
      $info = DB::table("infotoko")->get();

      $category = DB::table("category")->get();

      return response()->json([
          'info' => $info,
          'category' => $category
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
