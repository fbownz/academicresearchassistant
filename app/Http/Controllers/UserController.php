<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Validator;

use App\User;
use App\Http\Requests;

class UserController extends Controller
{
    // Update the User profile using the edit form on the profile settings page 

    public function update(Request $request)
    {
        

        $user = $request->user(); 
        if ($request->user()->name !== $request->name) {
            $user->name = $request->name;
        }  
        if ($request->user()->first_name !== $request->first_name) {
            $user->first_name = $request->first_name;
        }   
        if ($request->user()->last_name !== $request->last_name) {
            $user->last_name = $request->last_name;
        }  
        if ($request->user()->email !== $request->email) {
            $user->email = $request->email;
        }
        if ($request->user()->phone1 !== $request->phone1) {
            $user->phone1 = $request->phone1;
        }
        if ($request->user()->phone2 !== $request->phone2) {
            $user->phone2 = $request->phone2;
        }
        if ($request->user()->address !== $request->address) {
            $user->address= $request->address;
        }
        if ($request->user()->country !== $request->country) {
            $user->country= $request->country;
        }
        if ($request->user()->academic_level !== $request->academic_level) {
            $user->academic_level= $request->academic_level;
        }
        if ($request->user()->description !== $request->description) {
            $user->description = $request->description;
        }

        $user->update();
        if($request->file('prof_pic')) 
        {
            $prof_pic = $request->file('prof_pic');
            $prof_pic_name = 'avi'.$user->id.'.'. $prof_pic->getClientOriginalExtension();
            $prof_pic_origin = $prof_pic->move(storage_path('app/public/avatars'), $prof_pic_name);
            $prof_pic_url = asset('assets/public/avatars/'.$prof_pic_name);
            

            $user->prof_pic_url = $prof_pic_url;
            $user->prof_pic = $prof_pic_name;
        }
        $user->update();
        /*
            We are working with the picha_ya_id 
        */
        if ($request->file('picha_ya_id')) 
        {
            $picha_ya_id = $request->file('picha_ya_id');
            $picha_ya_id_name = 'kipande'.$user->id.'.'. $picha_ya_id->getClientOriginalExtension();
            $picha_ya_id_origin = $picha_ya_id->move(storage_path('app/public/vipande'), $picha_ya_id_name);
            $picha_ya_id_url = asset('assets/public/vipande/'.$picha_ya_id_name);
            
            // $user->picha_ya_id_url = $picha_ya_id_url; I decided not to save it so that 
            // we can just download it directly instead of showing it from the page instead of showing it as an image
            
            $user->picha_ya_id = $picha_ya_id_name;
            $user->picha_ya_id_mime = $picha_ya_id->getClientMimeType();
        }
        $user->update();        

        // We work with the resume files
        if ($request->file('resume')) 
        {
            $resume = $request->file('resume');
            $resume_name = 'cv'.$user->id.'.'.$resume->getClientOriginalExtension();
            $resume_origin = $resume->move(storage_path('app/public/resumes'), $resume_name);

            $user->resume = $resume_name ;
            $user->resume_mime = $resume->getClientMimeType();
                
        }
        $user->update();

        // We are now working with the certificate files
        if ($request->file('certificate')) {
            $certificate = $request->file('certificate');
            $certificate_name = 'cert'.$user->id.'.'.$certificate->getClientOriginalExtension();
            $certificate_origin = $certificate->move(storage_path('app/public/certs'), $certificate_name);

            $user->certificate = $certificate_name;
            $user->certificate_mime = $certificate->getClientMimeType();
        }
        
          
        $user->update();

        return back()->with('message', 'Profile updated successfully');
    }
    public function download(Request $request, $dl)
    {
        $user = $request->user();
        if($dl == 'id')
        {
            $disk='vipande';
            $file_name = $request->user()->picha_ya_id;
            $mime = $user->picha_ya_id_mime;

        }
        elseif ($dl == 'cv') 
        {
            $disk='resumes';
            $file_name = $request->user()->resume;
            $mime = $user->resume_mime;
        }
        elseif ($dl == 'cert') 
        {
            $disk='certs';
            $file_name = $request->user()->certificate;
            $mime = $user->certificate_mime;
        }
        else
        {
            $disk='local';
        }

        // $file_uploaded =Order_delivery_report::where('file_name','=', $file_name)->firstOrFail(); Testing not sure yet

        // We check if the file to be downloaded is available then give an error and stop if it isn't found
        if (!$file_name) {
            
            return back()->with("error", "Sorry we couldn't find your " .$dl." Kindly upload it one more time");
        } else {
            $file = Storage::disk($disk)->get($file_name);

        return (new Response($file, 200))
            ->header('Content-Type',$mime);
        }
        
        
    }
    // A function for the Admins to download user's vipande.
    public function admin_download_id(Request $request, $id){
        
        if ($request->user()->id == $id || !$request->user()->ni_admin) {
            return back()->with('error', "You are not an admin to download that document, if you believe this is a mistake kindly contact Support");
        } else {
            
            $user = User::findorfail($id);
        
            $disk='vipande';
            $file_name = $user->picha_ya_id;
            $mime = $user->picha_ya_id_mime;

            if (!$file_name) {
                return back()->with('error', 'No id found for '.$user->first_name .', Has the writer uploaded their ID?');
            } else {
                $file = Storage::disk($disk)->get($file_name);

                return (new Response($file, 200))
                    ->header('Content-Type',$mime);
                
            }
        }
        
    }

    // A function for the Admins to download user's cv.
    public function admin_download_cv(Request $request, $id){
        
        if ($request->user()->id == $id || !$request->user()->ni_admin) {

            return back()->with('error', "You are not an admin to download that document, if you believe this is a mistake kindly contact Support");
        } else {
            
            $user = User::findorfail($id);
        
            $disk='resumes';
            $file_name = $user->resume;
            $mime = $user->resume_mime;

            if (!$file_name) {
                return back()->with('error', 'No resume found for ' .$user->first_name. ', Has the writer uploaded their Resume?');
            } else {
                $file = Storage::disk($disk)->get($file_name);

                return (new Response($file, 200))
                    ->header('Content-Type',$mime);
            }
            

        }
        
    }

    // A function for the Admins to download user's cert.
    public function admin_download_cert(Request $request, $id){
        
        if ( $request->user()->id != $id ) {
            // We check if they are the owners of the files being requested
            if (!$request->user()->ni_admin) {
                return back()->with('error', "You are not an admin or document owner to download that document, if you believe this is a mistake kindly contact Support");
            }
            
        } else {
            
            $user = User::findorfail($id);
        
            $disk='certs';
            $file_name = $user->certificate;
            $mime = $user->certificate_mime;

            if (!$file_name) {
                return back()->with('error', 'No Cert found for '.$user->first_name. 'Has the writer uploaded their Cert?');

            } else {
                $file = Storage::disk($disk)->get($file_name);

                return (new Response($file, 200))
                    ->header('Content-Type',$mime);  
            } 

        }
        
    }

}
