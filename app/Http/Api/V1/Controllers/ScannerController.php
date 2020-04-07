<?php

namespace App\Http\Api\V1\Controllers;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Presenters\JsonApiPresenter;
use GuzzleHttp\Client;
use App\Services\Vulns\VulnService;
use App\Services\Scanners\ScannerService;

class ScannerController extends Controller
{
    private $service;
    protected $scanner_username;
    protected $scanner_password;
    protected $vuln;
    protected $scanner;

    public function __construct()
    {
        $this->presenter = new JsonApiPresenter;
        $this->client = new Client;
        $this->vuln = new VulnService;
        $this->scanner = new ScannerService;
        $this->endpoint = env("CORE_SCANNER_URL");
        $this->scanner_username = env("SCANNER_USERNAME");
        $this->scanner_password = env("SCANNER_PASSWORD");
    }

    public function getAuthScannerToken()
    {
        $scanner = $this->endpoint."/api-token-auth/";
        $client = $this->client->request("POST", $scanner, [
            'json' => [
                 'username' => $this->scanner_username,
                 'password' => $this->scanner_password
            ],
            'headers' => [
                "Content-Type" => "application/json"
            ]
        ]);
        return "JWT ".json_decode($client->getBody())->token;
    }

    public function launchScanner(Request $request)
    {
        $this->validate($request, [
            "scan_url" => "required"
        ]);
        $scanner = $this->endpoint."/api/webscan/";
        $client = $this->client->request("POST", $scanner, [
            'json' => [
                'scan_url' => $request->scan_url,
                'project_id' => env("SCANNER_ID"),
                'scanner' => 'zap_scan'
            ],
            'headers' => [
                "Content-Type" => "application/json",
                "Authorization" => $this->getAuthScannerToken()
            ]
        ]);
        return response()->json(json_decode($client->getBody()));
    }

    public function browseScanner()
    {
        return response()->json($this->getWebScanList());
        // return $this->presenter->renderPaginator($this->scanner->browse());
    }

    public function subdomainChecker(Request $request)
    {
        $this->validate($request, [
            "url" => "required"
        ]);
        $subdomain = "https://api.indoxploit.or.id/domain/".$request->url;
        $client = $this->client->request("GET", $subdomain);
        $responses = json_decode($client->getBody());
        return response()->json($responses);
    }

    public function checkDomain(Request $request)
    {
        $this->validate($request, [
            "url" => "required"
        ]);
        try {
            $client = $this->client->request("GET", $request->url);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $client = $e->getResponse();
        }
        if ($client == null) {
            $data = [
                "status" => "down"
            ];
            return response()->json($data);
        } else {
            $responses = $client->getStatusCode();
            if ($responses == 200 || $responses == 302) {
                $data = [
                    "status" => "up"
                ];
            } else {
                $data = [
                    "status" => "down"
                ];
            }
            return response()->json($data);
        }
    }

    public function syncScanner()
    {
        $lists = $this->getWebScanList();
        foreach ($lists as $key => $value) {
            $this->scanner->updateOrCreate([
                "scan_url" => $value->scan_url,
            ], [
                "scan_url" => $value->scan_url,
                "project_id" => $value->project_id,
                "scan_scanid" => $value->scan_scanid,
                "vul_status" => $value->vul_status,
                "total_vul" => $value->total_vul,
                "high_vul" => $value->high_vul,
                "medium_vul" => $value->medium_vul,
                "low_vul" => $value->low_vul
            ]);
        }
        return response()->json([
            "status" => 1
        ]);
    }

    public function findScanner($id)
    {
        $scanner = $this->scanner->findScannerByScannerId($id);
        return $this->presenter->render($scanner);
    }

    public function getWebScanList()
    {
        $scanner = $this->endpoint."/api/webscan/";
        $client = $this->client->request("GET", $scanner, [
            "headers" => [
                "Authorization" => $this->getAuthScannerToken()
            ]
        ]);
        return json_decode($client->getBody());
    }

    public function resultSave()
    {
        $scanner = $this->endpoint."/api/webscanresult/";
        $client = $this->client->request("GET", $scanner, [
            "headers" => [
                "Authorization" => $this->getAuthScannerToken()
            ]
        ]);
        $responses = json_decode($client->getBody());
        foreach ($responses as $key => $value) {
            $this->vuln->updateOrCreate([
                "vuln_id" => $value->vuln_id
            ], [
                'project_id' => $value->project_id,
                'scan_id' => $value->scan_id,
                'vuln_id' => $value->vuln_id,
                'confidence' => $value->confidence,
                'wascid' => $value->wascid,
                'cweid' => $value->cweid,
                'risk' => $value->risk,
                'reference' => $value->reference,
                'name' => $value->name,
                'solution' => $value->solution,
                'param' => $value->param,
                'evidence' => $value->evidence,
                'sourceid' => $value->sourceid,
                'pluginId' => $value->pluginId,
                'other' => $value->other,
                'attack' => $value->attack,
                'messageId' => $value->messageId,
                'method' => $value->method,
                'alert' => $value->alert,
                'ids' => $value->ids,
                'description' => $value->description,
                'req_res' => $value->req_res,
                'note' => $value->note,
                'rtt' => $value->rtt,
                'tags' => $value->tags,
                'timestamp' => $value->timestamp,
                'responseHeader' => $value->responseHeader,
                'requestBody' => $value->requestBody,
                'responseBody' => $value->responseBody,
                'requestHeader' => $value->requestHeader,
                'cookieParams' => $value->cookieParams,
                'res_type' => $value->res_type,
                'res_id' => $value->res_id,
                'date_time' => $value->date_time,
                'false_positive' => $value->false_positive
            ]);
        }
        return response()->json([
            "status" => 1
        ]);
    }

    public function scannerResult()
    {
        $results = $this->vuln->browse();
        return $this->presenter->renderPaginator($results);
    }

    public function showByScannerId($id)
    {
        $scanner = $this->endpoint."/api/webscanresult/";
        $client = $this->client->request("POST", $scanner, [
            'json' => [
                'scan_id' => $id,
            ],
            "headers" => [
                "Authorization" => $this->getAuthScannerToken()
            ]
        ]);
        $responses = json_decode($client->getBody());
        $data = [];
        foreach ($responses as $key => $value) {
            $params = explode(",", $value->param);
            $data[] = [
                "scan_id" => $value->scan_id,
                "project_id" => $value->project_id,
                "url" => $value->url,
                "vuln_id" => $value->vuln_id,
                "confidence" => $value->confidence,
                "wascid" => $value->wascid,
                "cweid" => $value->cweid,
                "risk" => $value->risk,
                "reference" => $value->reference,
                "name" => $value->name,
                "solution" => $value->solution,
                "param" => $params,
                "evidence" => $value->evidence,
                "sourceid" => $value->sourceid,
                "pluginId" => $value->pluginId,
                "other" => $value->other,
                "attack" => $value->attack,
                "messageId" => $value->messageId,
                "method" => $value->method,
                "alert" => $value->alert,
                "ids" => $value->ids,
                "description" => $value->description,
                "req_res" => $value->req_res,
                "note" => $value->note,
                "rtt" => $value->rtt,
                "tags" => $value->tags,
                "timestamp" => $value->timestamp,
                "responseHeader" => $value->responseHeader,
                "requestBody" => $value->requestBody,
                "responseBody" => $value->responseBody,
                "requestHeader" => $value->requestHeader,
                "cookieParams" => $value->cookieParams,
                "res_type" => $value->res_type,
                "res_id" => $value->res_id,
                "date_time" => $value->date_time,
                "false_positive" => $value->false_positive
            ];
        }
        return response()->json($data);

        // $scanner = $this->vuln->findByScannerId($id);
        // return $this->presenter->renderPaginator($scanner);
    }

    public function read($id)
    {
        $vuln = $this->vuln->read($id);
        if ($vuln) {
            return $this->presenter->render($vuln, 200);
        }
        return $this->notFountSetValue();
    }

    private function notFountSetValue()
    {
        return response()->json([
            'meta' => [
                'status' => "Not Found",
            ]
        ], 404);
    }
}
