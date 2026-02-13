<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Models\_setting as m_setting;

function debug(){
    $debug = config('app.env') == "local";
    return $debug;
}

function isWindows(){
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}

function compose($status, $msg, $payload = null, $statusCode = 200) {
    $reply = array(
        "SENDER" => "SMBM Mts Negeri 2 Kota Semarang",
        "STATUS" => $status,
        "MESSAGE" => $msg,
        "PAYLOAD" => $payload
    );
    return response()->json($reply, $statusCode);
}

function compose2($status, $msg, $payload = null) {
    $reply = json_encode(array(
        "SENDER" => "SMBM Mts Negeri 2 Kota Semarang",
        "STATUS" => $status,
        "MESSAGE" => $msg,
        "PAYLOAD" => $payload));
    return Response::make($reply, "200")->header("Content-Type", "application/json");
}

function tanggal($tgl, $mode, $timeSegment = "SECOND") {
    if($tgl != "" && $mode != "" && $tgl!= "0000-00-00" && $tgl != "0000-00-00 00:00:00") {
        $t = explode("-",$tgl);
        $bln = array();
        $bln["01"]["LONG"] = "Januari";
        $bln["01"]["SHORT"] = "Jan";
        $bln["1"]["LONG"] = "Januari";
        $bln["1"]["SHORT"] = "Jan";
        $bln["02"]["LONG"] = "Februari";
        $bln["02"]["SHORT"] = "Feb";
        $bln["2"]["LONG"] = "Februari";
        $bln["2"]["SHORT"] = "Feb";
        $bln["03"]["LONG"] = "Maret";
        $bln["03"]["SHORT"] = "Mar";
        $bln["3"]["LONG"] = "Maret";
        $bln["3"]["SHORT"] = "Mar";
        $bln["04"]["LONG"] = "April";
        $bln["04"]["SHORT"] = "Apr";
        $bln["4"]["LONG"] = "April";
        $bln["4"]["SHORT"] = "Apr";
        $bln["05"]["LONG"] = "Mei";
        $bln["05"]["SHORT"] = "Mei";
        $bln["5"]["LONG"] = "Mei";
        $bln["5"]["SHORT"] = "Mei";
        $bln["06"]["LONG"] = "Juni";
        $bln["06"]["SHORT"] = "Jun";
        $bln["6"]["LONG"] = "Juni";
        $bln["6"]["SHORT"] = "Jun";
        $bln["07"]["LONG"] = "Juli";
        $bln["07"]["SHORT"] = "Jul";
        $bln["7"]["LONG"] = "Juli";
        $bln["7"]["SHORT"] = "Jul";
        $bln["08"]["LONG"] = "Agustus";
        $bln["08"]["SHORT"] = "Ags";
        $bln["8"]["LONG"] = "Agustus";
        $bln["8"]["SHORT"] = "Ags";
        $bln["09"]["LONG"] = "September";
        $bln["09"]["SHORT"] = "Sep";
        $bln["9"]["LONG"] = "September";
        $bln["9"]["SHORT"] = "Sep";
        $bln["10"]["LONG"] = "Oktober";
        $bln["10"]["SHORT"] = "Okt";
        $bln["11"]["LONG"] = "November";
        $bln["11"]["SHORT"] = "Nov";
        $bln["12"]["LONG"] = "Desember";
        $bln["12"]["SHORT"] = "Des";

        $b = $t[1];

        if (strpos($t[2], ":") === false) { //tdk ada format waktu
            $jam = "";
        }
        else {
            $j = explode(" ",$t[2]);
            $t[2] = $j[0];
            $jam = $j[1];
            if($timeSegment == "HOUR"){
                $jam = substr($jam, 0, 2);
            }else if($timeSegment == "MINUTE"){
                $jam = substr($jam, 0, 5);
            }
        }

        return $t[2]." ".$bln[$b][$mode]." ".$t[0]." ".$jam;
    }
    else {
        return "-";
    }
}

function bulanIndo($tgl,$mode = "LONG") {
    if($tgl == "" || $mode == "" || $tgl == "0000-00"){
        return "-";
    }
    $t = explode("-", $tgl);
    $bln["01"]["LONG"] = "Januari";
    $bln["01"]["SHORT"] = "Jan";
    $bln["1"]["LONG"] = "Januari";
    $bln["1"]["SHORT"] = "Jan";
    $bln["02"]["LONG"] = "Februari";
    $bln["02"]["SHORT"] = "Feb";
    $bln["2"]["LONG"] = "Februari";
    $bln["2"]["SHORT"] = "Feb";
    $bln["03"]["LONG"] = "Maret";
    $bln["03"]["SHORT"] = "Mar";
    $bln["3"]["LONG"] = "Maret";
    $bln["3"]["SHORT"] = "Mar";
    $bln["04"]["LONG"] = "April";
    $bln["04"]["SHORT"] = "Apr";
    $bln["4"]["LONG"] = "April";
    $bln["4"]["SHORT"] = "Apr";
    $bln["05"]["LONG"] = "Mei";
    $bln["05"]["SHORT"] = "Mei";
    $bln["5"]["LONG"] = "Mei";
    $bln["5"]["SHORT"] = "Mei";
    $bln["06"]["LONG"] = "Juni";
    $bln["06"]["SHORT"] = "Jun";
    $bln["6"]["LONG"] = "Juni";
    $bln["6"]["SHORT"] = "Jun";
    $bln["07"]["LONG"] = "Juli";
    $bln["07"]["SHORT"] = "Jul";
    $bln["7"]["LONG"] = "Juli";
    $bln["7"]["SHORT"] = "Jul";
    $bln["08"]["LONG"] = "Agustus";
    $bln["08"]["SHORT"] = "Ags";
    $bln["8"]["LONG"] = "Agustus";
    $bln["8"]["SHORT"] = "Ags";
    $bln["09"]["LONG"] = "September";
    $bln["09"]["SHORT"] = "Sep";
    $bln["9"]["LONG"] = "September";
    $bln["9"]["SHORT"] = "Sep";
    $bln["10"]["LONG"] = "Oktober";
    $bln["10"]["SHORT"] = "Okt";
    $bln["11"]["LONG"] = "November";
    $bln["11"]["SHORT"] = "Nov";
    $bln["12"]["LONG"] = "Desember";
    $bln["12"]["SHORT"] = "Des";

    return $bln[$t[1]][$mode] . " " . $t[0];
}

function randomDigits($length){
    $digits = "";
    $numbers = range(0,9);
    shuffle($numbers);
    for($i = 0;$i < $length;$i++) {
        $digits .= $numbers[$i];
    }
    return $digits;
}

function createCode($codeLength) {
    $kode = strtoupper(substr(md5(randomDigits($codeLength)), 0,($codeLength-1) ));

    return $kode;
}

function getReferences($ctg) {
    $ref = DB::table("_reference")
        ->where("R_CATEGORY",$ctg)
        ->orderBy("R_ORDER", "ASC")
        ->get();

    if(isset($ref) || $ref > 0) {
        return $ref;
    }
    else {
        return [];
    }
}

function getSetting($setId)  {
    $setValue = "";
    $setting = m_setting::find($setId);
    if($setting){
        $setValue = $setting->S_VALUE;
    }

    return $setValue;
}


function logcmd(...$args): void {
    $stdout = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');

    foreach ($args as $arg) {
        if (is_object($arg) || is_array($arg) || is_resource($arg)) {
            $output = print_r($arg, true);
        } else {
            $output = (string) $arg;
        }
        fwrite($stdout, $output . PHP_EOL);
    }
}
    

function curlRequest($url, $method = "GET", $query = [], $body = [], $asJson = true){
    $FUNC_TAG = "[curlRequest] ";
    logcmd($FUNC_TAG."--------------------");

    $method = strtoupper($method);

    // Tambahkan query ke URL jika ada
    if ($query && !empty($query)) {
        $url .= (strpos($url, "?") === false ? "?" : "&") . http_build_query($query);
    }

    $ch = curl_init();

    $headers = [
        "Accept: application/json",
    ];

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
    ];

    // Body handling
    if (in_array($method, ["POST", "PUT", "PATCH", "DELETE"])) {
        if ($asJson) {
            $payload = json_encode($body);
            $headers[] = "Content-Type: application/json";
            $options[CURLOPT_POSTFIELDS] = $payload;
        } else {
            $options[CURLOPT_POSTFIELDS] = http_build_query($body);
        }
    }

    $options[CURLOPT_HTTPHEADER] = $headers;

    curl_setopt_array($ch, $options);

    logcmd($url);
    logcmd($method);
    logcmd("headers:", json_encode($headers));
    logcmd("query:", json_encode($query));
    logcmd("body:", json_encode($body));
    
    logcmd("started...");

    $response = curl_exec($ch);
    logcmd("curl_exec...");
    logcmd("response: ", $response);

    $error = curl_error($ch);
    logcmd("curl curl_error...");
    logcmd("error: ", $error);

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    logcmd("curl curl_getinfo...");
    logcmd("status: ", $status);

    curl_close($ch);
    logcmd("curl closed...");

    if ($error) {
        return ["error" => $error];
    }

    // Decode JSON jika memungkinkan
    $decoded = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return [
            "status" => $status,
            "data" => $decoded
        ];
    }

    return [
        "status" => $status,
        "data" => $response
    ];
}

function romawi($number){
    $map = [
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1,
    ];

    $returnValue = '';

    foreach ($map as $roman => $value) {
        while ($number >= $value) {
            $returnValue .= $roman;
            $number -= $value;
        }
    }

    return $returnValue;
}


function prakagGrupNama($grup, $reverse = false){
    $map = [
        "AH" => "ASMAUL HUSNA",
        "SD" => "SHALAT DHUHA",
        "SW" => "SHALAT WAJIB",
        "DZ" => "DZIKIR",
        "BQ" => "BACA QUR'AN",
        "SJ" => "SHALAT JENAZAH",
        "YT" => "PEMBACAAN TAHLIL",
        //"PT" => "PEMBACAAN TAHLIL",
        "DH" => "DO'A HARIAN",
        "PS" => "PEMBACAAN SHOLAWAT",
        "KI" => "KETERTIBAN IBADAH",
        "WD" => "WUDHU",
    ];
    if($reverse) $map = array_flip($map);
    return $map[$grup] ?? "";
}


function nilaiToGrade($avg) {
    if ($avg >= 94 && $avg <= 100) return "A";
    if ($avg >= 87 && $avg <= 93)  return "B";
    if ($avg >= 80 && $avg <= 86)  return "C";
    if ($avg >= 73 && $avg <= 79)  return "D";
    return "D";
}


function copyR2File($filename, $folder = 'public')
{
    if (!$filename || $filename == "-") return '';

    $oldPath = $folder.'/'.$filename;

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $newFilename = Str::uuid().($ext ? '.'.$ext : '');
    $newPath = $folder.'/'.$newFilename;

    $client = Storage::disk('s3')
        ->getDriver()
        ->getAdapter()
        ->getClient();

    try {
        $client->copyObject([
            'Bucket'     => config('filesystems.disks.s3.bucket'),
            'CopySource' => config('filesystems.disks.s3.bucket').'/'.$oldPath,
            'Key'        => $newPath,
        ]);
    } catch (\Throwable $e) {
        logcmd($e->getMessage());
        return '';
    }

    return $newFilename;
}