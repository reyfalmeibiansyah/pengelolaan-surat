<?php

namespace App\Http\Controllers;

use App\Models\result;
use App\Models\letter;
use App\Models\letter_type;
use App\Models\User;
use Illuminate\Http\Request;
use Excel;
use PDF;
use App\Exports\AllLetterExport;

class LetterController extends Controller
{

    public function index()
    {
        $letters = Letter::all();

        return view('surat.index', compact('letters'));
    }

    public function getLetters()
    {

        $letters = Letter::orderBy('letter_type_id', 'ASC')->simplePaginate(5);
        $letterTypes = letter_type::get(); 
        $results = Result::get();

        $letterCounts = [];

        foreach ($letters as $letter) {
            $recipientId = json_decode($letter->recipients, true);

            $letterTypeId = letter_type::find($letter->letter_type_id);

            $letter->letterTypeId = $letterTypeId;

            $recipients = User::whereIn('id', $recipientId)->get();

            $letter->recipientsData = $recipients;

            $notulisUser = User::find($letter->notulis);

            $letter->notulisUserData = $notulisUser;

            if (!isset($letterCounts[$letter->letter_type_id])) {
                $letterCounts[$letter->letter_type_id] = 1;
            } else {
                $letterCounts[$letter->letter_type_id]++;
            }
        }   

        return view('letter.letters.index', compact('letters', 'results', 'letterTypes', 'letterCounts'));
    }




    public function searchLetters(Request $request)
    {
        $keyword = $request->input('name');
        $letters = letter::where('letter_perihal', 'like', "%$keyword%")->orderBy('letter_type_id', 'ASC')->simplePaginate(5);
        $results = result::get();

        $recipientsArray = [];

        $users = []; 

        foreach ($letters as $letter) {
            $recipientsArray[$letter->id] = explode(' ', $letter->recipients);

            $user = User::find($letter->notulis);

            $users[$letter->id] = $user;
        }

        return view('letter.letters.index', compact('letters', 'recipientsArray', 'users', 'results'));
    }
    public function createLetters()
    {
        $classificate = letter_type::get();
        $user = User::where('role', 'guru')->get();
        
        return view('letter.letters.createLetter.create', compact('user', 'classificate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'letter_type_id' => 'required',
            'letter_perihal' => 'required',
            'recipients' => 'required|array',
            'content' => 'required',
            'notulis' => 'required'
        ]);
       
        letter::create([
            'letter_type_id' => $request->letter_type_id,
            'letter_perihal' => $request->letter_perihal,
            'recipients' => json_encode($request->recipients),
            'content' => $request->content,
            'attachment' => $request->attachment,
            'notulis' => $request->notulis
        ]);

        return redirect()->route('letter.letters.data')->with('success', 'Berhasil Menambahkan Surat Baru!');
    }

    public function downloadPDF($id) {
        set_time_limit(300); 

        $letter = letter::find($id);
        $name = $letter->letter_perihal;
        $recipientsArray = [];
        $recipientsArray[$letter->id] = explode(' ', $letter->recipients);
        $pdf = PDF::loadView('letter.letters.download', compact('letter', 'recipientsArray'));
        return $pdf->download($name . '.pdf');
    }
    

    public function downloadExcel(){
        $file_name = 'Klasifikasi Surat.xlsx';
        return Excel::download(new AllLetterExport, $file_name);
    }
    
    public function show(letter $letter, $id)
    {
        $letterType = letter_type::get();
        $letter = letter::find($id);
        $user = User::where('role', 'guru')->get();

        $recipientsArray = [];
        $recipientsArray[$letter->id] = json_decode($letter->recipients, true);
        

        return view('letter.letters.result', compact('user', 'letter', 'letterType', 'recipientsArray'));
    }


    public function showw(letter $letter, $id)
    {
        $letterType = letter_type::get();
        $letter = letter::find($id);
        $user = User::where('role', 'guru')->get();

        $recipientsArray = [];

        $recipientsArray[$letter->id] = json_decode($letter->recipients, true);
        

        return view('result.index', compact('user', 'letter', 'letterType', 'recipientsArray'));
    }

    public function edit($id)
    {
        $letterType = letter_type::get();
        $letters = letter::find($id);
        $user = User::where('role', 'guru')->get();

        return view('letter.letters.edit', compact('user', 'letters', 'letterType'));
    }


    public function update(Request $request, letter $letter, $id)
    {
        $request->validate([
            'letter_type_id' => 'required',
            'letter_perihal' => 'required',
            'recipients' => 'required|array',
            'content' => 'required',
            'notulis' => 'required'
        ]);
       
        letter::where('id', $id)->update([
            'letter_type_id' => $request->letter_type_id,
            'letter_perihal' => $request->letter_perihal,
            'recipients' => json_encode($request->recipients),
            'content' => $request->content,
            'attachment' => $request->attachment,
            'notulis' => $request->notulis
        ]);

        return redirect()->route('letter.letters.data')->with('success', 'Berhasil Mengubah Data Surat!');
    }

    public function destroy($id)
    {
        letter::where('id', $id)->delete();
        return redirect()->back()->with('delete', 'Berhasil Menghapus Data Surat');
    }
}
