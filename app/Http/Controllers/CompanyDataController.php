<?php

namespace App\Http\Controllers;

use App\Clients\DadataClient;
use App\Clients\RamisClient;
use App\Clients\WarmsClient;

use App\Services\ClientsService;
use Illuminate\Http\Request;

class CompanyDataController extends Controller
{
    private $clients = [
        RamisClient::class,
        WarmsClient::class,
        DadataClient::class
    ];

    public function __construct(ClientsService $clientsService)
    {
        $this->clientsService = $clientsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = new $this->clientsService($this->clients);
        $arrayClients = $service->getRedisClientsStats();
        $dataCompany = $service->getOrganizationByInn(0276073077);
        return view('index', ['arrayClients' => $arrayClients, 'json' => $dataCompany]);
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
        $service = new $this->clientsService($this->clients);
        return $service->getOrganizationByInn($id) ?? response()->json(null, 204);
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
