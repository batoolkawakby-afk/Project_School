<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
     public function registerStudent(Request $request)
    {
      
        $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName'=>'required|string|max:100',
           'personalPhoto'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'class'=>'required|string|max:100',   
            'division'=>'required|string|max:100',  
            'mobile' => 'required|string|max:10|unique:students,mobile',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->hasFile('personalPhoto')) {
            $image = $request->file('personalPhoto');
            $personalPhotoName = time() . '_personal.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $personalPhotoName);
            $personalPhotoPath = 'images/' . $personalPhotoName;
        } else {
            return response()->json(['error' => 'The personal photo was not uploaded'], 400);
        }

   

     
        $student = Student::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,   
            
            'personalPhoto' => $personalPhotoPath,          
            'class'=>      $request->class   ,  
            'division'=>      $request->division   ,    
           
            
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);


         User::create([
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'userType' => 'student', 
            'related_id' => $student->id, 
        ]);

        return response()->json([
            'message' => 'teacher registered successfully',
            'teacher' => $student   
        ], 201);
    }








}
