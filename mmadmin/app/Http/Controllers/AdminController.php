<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\RedirectResponse;


use Auth;
use Excel;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users AS u')
                    ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                    ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                    ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                    ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                    ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                    ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                    ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3)')
                    ->orderBy('u.created_at', 'desc')
                    ->paginate(10);
        
        return view('index', ['users'=> $users]);
    }
	
    public function userView($id)
    {
            $userDetails = DB::select('
                    SELECT 
                            `u`.`id`
                            , `u`.`name`
                            , `ud`.`IC/Passport` AS username 
                            , `u`.`email` 
                            , `ud`.`PhoneNumber`
                            , `rs`.`isRedeemed` 
                            , `r`.`id` AS redeemID 
                            , `ua`.`name` AS AdminName 
                            , `rs`.`created_at` RedeemDate 
                            , `r`.`pdf` AS PDFFile 
                            , `sl`.`name` AS SourceName 
                    FROM 
                            `users` `u` 
                            LEFT JOIN `users_details` `ud` ON `u`.`id` = `ud`.`idUser` 
                            LEFT JOIN `redemptions` `r` ON `u`.`id` = `r`.`idUser` 
                            LEFT JOIN `redemption_statuses` `rs` ON `r`.`id` = `rs`.`idRedemption` 
                            LEFT JOIN `users` `ua` ON `ua`.`id` = `rs`.`idAdmin` 
                            LEFT JOIN `source_lead` `sl` ON `sl`.`id` = `u`.`idSourceLead` 
                    WHERE 
                            (`u`.`id` = '.$id.') 
            ');
            return view('user.view', ['userDetails'=>$userDetails, 'id'=>$id]);
    }
        
    public function userClaim($rid)
    {
        $userDetails = DB::select('
                SELECT 
                        `u`.`id` AS UserID 
                        , `u`.`name`
                        , `ud`.`IC/Passport` AS username 
                        , `u`.`email` 
                        , `ud`.`PhoneNumber`
                        , `rs`.`isRedeemed` 
                        , `r`.`id` AS redeemID 
                        , `ua`.`name` AS AdminName 
                        , `rs`.`created_at` RedeemDate 
                        , `r`.`pdf` AS PDFFile 
                        , `sl`.`name` AS SourceName 
                FROM 
                        `users` `u` 
                        LEFT JOIN `users_details` `ud` ON `u`.`id` = `ud`.`idUser` 
                        LEFT JOIN `redemptions` `r` ON `u`.`id` = `r`.`idUser` 
                        LEFT JOIN `redemption_statuses` `rs` ON `r`.`id` = `rs`.`idRedemption` 
                        LEFT JOIN `users` `ua` ON `ua`.`id` = `rs`.`idAdmin` 
                        LEFT JOIN `source_lead` `sl` ON `sl`.`id` = `u`.`idSourceLead` 
                WHERE 
                        (`r`.`id` = '.$rid.') 
        ');

        foreach ($userDetails as $userDetail)
        {
            $id = $userDetail->UserID;
        }

        $id_redemption_statuses = DB::table('redemption_statuses')->insertGetId(
                [
                    'idAdmin' => Auth::user()->id, 
                    'idRedemption' => $rid,
                    'isRedeemed' => 1
                ]
        );

        //return view('user.view', ['userDetails'=>$userDetails, 'id'=>$id]);
        //return Redirect::to('/mmadmin/user/' . $id);
        return redirect('/user/' . $id);
    }

    public function userSearch()
    {
        $src = $_GET['src'];
        $rs = $_GET['rs'];
        
        if ($src == 'null') 
        {
            $src = '';
        }
        else
        {
            $src = $_GET['src'];
        }
        
        if ($src != "" && $rs != "")
        {
            if ($rs == 1)
            {
                $users = DB::table('users AS u')
                            ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                            ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                            ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                            ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                            ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                            ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                            ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`u`.`name` LIKE \'%' . $src . '%\' OR `u`.`email` LIKE \'%' . $src . '%\' OR `ud`.`IC/Passport` LIKE \'%' . $src . '%\' OR `ud`.`PhoneNumber` LIKE \'%' . $src . '%\' OR (CONCAT(\'MM\', CAST(`u`.`id` AS CHAR), CAST(`r`.`id` AS CHAR))) LIKE \'%' . $src . '%\') AND (`rs`.`isRedeemed` = 1)')
                            ->orderBy('u.created_at', 'desc')
                            ->paginate(10);
            }
            else if ($rs >= 2)
            {
                $users = DB::table('users AS u')
                            ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                            ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                            ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                            ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                            ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                            ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                            ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`u`.`name` LIKE \'%' . $src . '%\' OR `u`.`email` LIKE \'%' . $src . '%\' OR `ud`.`IC/Passport` LIKE \'%' . $src . '%\' OR `ud`.`PhoneNumber` LIKE \'%' . $src . '%\' OR (CONCAT(\'MM\', CAST(`u`.`id` AS CHAR), CAST(`r`.`id` AS CHAR))) LIKE \'%' . $src . '%\') AND (`rs`.`isRedeemed` IS NULL)')
                            ->orderBy('u.created_at', 'desc')
                            ->paginate(10);
            }
                
        }
        else if ($src != "" && $rs == "")
        {
            $users = DB::table('users AS u')
                            ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                            ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                            ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                            ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                            ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                            ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                            ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`u`.`name` LIKE \'%' . $src . '%\' OR `u`.`email` LIKE \'%' . $src . '%\' OR `ud`.`IC/Passport` LIKE \'%' . $src . '%\' OR `ud`.`PhoneNumber` LIKE \'%' . $src . '%\' OR (CONCAT(\'MM\', CAST(`u`.`id` AS CHAR), CAST(`r`.`id` AS CHAR))) LIKE \'%' . $src . '%\')')
                            ->orderBy('u.created_at', 'desc')
                            ->paginate(10);
            
        }
        else if ($src == "" && $rs != "")
        {
            if ($rs == 1)
            {
                $users = DB::table('users AS u')
                            ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                            ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                            ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                            ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                            ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                            ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                            ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`rs`.`isRedeemed` = 1)')
                            ->orderBy('u.created_at', 'desc')
                            ->paginate(10);
            }
            else if ($rs >= 2)
            {
                $users = DB::table('users AS u')
                            ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                            ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                            ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                            ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                            ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                            ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                            ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`rs`.`isRedeemed` IS NULL)')
                            ->orderBy('u.created_at', 'desc')
                            ->paginate(10);
            }
            
        }
        else if ($src == "" && $rs == "")
        {
            $users = DB::table('users AS u')
                            ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                            ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                            ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                            ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                            ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                            ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                            ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) ')
                            ->orderBy('u.created_at', 'desc')
                            ->paginate(10);
        }

        return view('user.search', ['users'=>$users->appends(Input::except('page'))]);
    }
    
    public function export()
    {
        $export =   Excel::create('Melawati_Mall_Registration', function($excel) {
                        $excel->sheet('Registrants', function($sheet) {
                            $sheet->setOrientation('landscape');
                            $users1 = DB::table('users AS u')
                                        ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                                        ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                                        ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                                        ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                                        ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                                        ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                                        ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3)')
                                        ->orderBy('u.created_at', 'desc')
                                        ->get();
                            $sheet->loadView('indexexport', array('users'=>$users1));
                        });
                    })->export('xls');
                    
        return $export;
    }
    
    public function userSearchExport()
    {
        $export =   Excel::create('Melawati_Mall_Registration', function($excel) {
                        $excel->sheet('Registrants', function($sheet) {
                            $sheet->setOrientation('landscape');
                            $src = $_GET['src'];
                            $rs = $_GET['rs'];
                            if ($src != "" && $rs != "")
                            {
                                if ($rs == 1)
                                {
                                    $users = DB::table('users AS u')
                                                ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                                                ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                                                ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                                                ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                                                ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                                                ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                                                ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`u`.`name` LIKE \'%' . $src . '%\' OR `u`.`email` LIKE \'%' . $src . '%\' OR `ud`.`IC/Passport` LIKE \'%' . $src . '%\' OR `ud`.`PhoneNumber` LIKE \'%' . $src . '%\') AND (`rs`.`isRedeemed` = 1)')
                                                ->orderBy('u.created_at', 'desc')
                                                ->get();
                                }
                                else if ($rs >= 2)
                                {
                                    $users = DB::table('users AS u')
                                                ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                                                ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                                                ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                                                ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                                                ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                                                ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                                                ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`u`.`name` LIKE \'%' . $src . '%\' OR `u`.`email` LIKE \'%' . $src . '%\' OR `ud`.`IC/Passport` LIKE \'%' . $src . '%\' OR `ud`.`PhoneNumber` LIKE \'%' . $src . '%\') AND (`rs`.`isRedeemed` IS NULL)')
                                                ->orderBy('u.created_at', 'desc')
                                                ->get();
                                }

                            }
                            else if ($src != "" && $rs == "")
                            {
                                $users = DB::table('users AS u')
                                                ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                                                ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                                                ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                                                ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                                                ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                                                ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                                                ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`u`.`name` LIKE \'%' . $src . '%\' OR `u`.`email` LIKE \'%' . $src . '%\' OR `ud`.`IC/Passport` LIKE \'%' . $src . '%\' OR `ud`.`PhoneNumber` LIKE \'%' . $src . '%\')')
                                                ->orderBy('u.created_at', 'desc')
                                                ->get();

                            }
                            else if ($src == "" && $rs != "")
                            {
                                if ($rs == 1)
                                {
                                    $users = DB::table('users AS u')
                                                ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                                                ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                                                ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                                                ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                                                ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                                                ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                                                ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`rs`.`isRedeemed` = 1)')
                                                ->orderBy('u.created_at', 'desc')
                                                ->get();
                                }
                                else if ($rs >= 2)
                                {
                                    $users = DB::table('users AS u')
                                                ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                                                ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                                                ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                                                ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                                                ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                                                ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                                                ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) AND (`rs`.`isRedeemed` IS NULL)')
                                                ->orderBy('u.created_at', 'desc')
                                                ->get();
                                }
                            }
                            else if ($src == "" && $rs == "")
                            {
                                $users = DB::table('users AS u')
                                                ->select('u.id', 'u.name', 'ud.IC/Passport AS username', 'rs.isRedeemed', 'u.email', 'ud.PhoneNumber', 'rs.created_at AS RedeemedDate', 'sl.name AS SourceName')
                                                ->leftJoin('users_roles AS ur', 'u.id', '=', 'ur.idUser')
                                                ->leftJoin('users_details AS ud', 'u.id', '=', 'ud.idUser')
                                                ->leftJoin('redemptions AS r', 'u.id', '=', 'r.idUser')
                                                ->leftJoin('source_lead AS sl', 'u.idSourceLead', '=', 'sl.id')
                                                ->leftJoin('redemption_statuses AS rs', 'r.id', '=', 'rs.idRedemption')
                                                ->whereRaw('(`u`.`enabled` = 1) AND (`ur`.`idRole` = 3) ')
                                                ->orderBy('u.created_at', 'desc')
                                                ->get();
                            }
                            $sheet->loadView('indexexport', array('users'=>$users));
                        });
                    })->export('xls');
                    
        return $export;
    }
}
