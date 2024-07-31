<?php

namespace App\Http\Controllers;

use App\Models\IDCard;
use App\Models\User;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Manajemen User';
        if ($request->ajax()) {
            $data = User::where('role_id', '!=', 1)->where('role_id', '!=', 2)->orderBy('id', 'ASC')->get();
            return Datatables::of($data)
                ->addColumn('email', function ($data) {
                    $email = $data->email ? $data->email : '<span class="text-danger"><i>empty</i></span>';
                    return $email;
                })
                ->addColumn('role', function ($data) {
                    if ($data->role_id == 1) {
                        $role = "Admin";
                    } elseif ($data->role_id == 2) {
                        $role = "Operator";
                    } elseif ($data->role_id == 3) {
                        $role = "Guru";
                    } elseif ($data->role_id == 4) {
                        $role = "Guru BK";
                    } elseif ($data->role_id == 5) {
                        $role = "Kepala Sekolah";
                    } elseif ($data->role_id == 6) {
                        $role = "Siswa";
                    } elseif ($data->role_id == 7) {
                        $role = "Orang Tua";
                    }
                    return '<center>' . $role . '</center>';
                })
                ->addColumn('action', function ($row) {
                    return '<center><div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item edit"
                                data-bs-toggle="offcanvas" data-bs-target="#ajaxModel" aria-controls="ajaxModel"
                                data-id="' . $row->id . '">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button type="button" class="dropdown-item delete" data-bs-toggle="modal"
                            data-bs-target="#ajaxModelHps" data-id="' . $row->id . '">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div></center>';
                })
                ->rawColumns(['action', 'email', 'role'])
                ->make(true);
        }

        return view('user.index', compact('menu'));
    }

    public function store(Request $request)
    {
        $message = array(
            'name.required'         => 'Nama User harus diisi.',
            'email.required'        => 'Email harus diisi.',
            'email.email'           => 'Penulisan email tidak benar.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password harus diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'repassword.required'   => 'Harap konfirmasi password.',
            'repassword.same'       => 'Password harus sama.',
            'repassword.min'        => 'Password minimal 8 karakter.',
            'role_id.required'      => 'Role harus di dipilih.',
        );

        if ($request->hidden_id) {
            $ruleEmail      = 'nullable|email';
            $rulePassword   = 'nullable|min:8';
            $ruleRePassword = 'nullable|same:password|min:8';
            $ruleRole       = 'nullable';
        } else {
            $ruleEmail      = 'required|email|unique:users,email';
            $rulePassword   = 'required|min:8';
            $ruleRePassword = 'required|same:password|min:8';
            $ruleRole       = 'required';
        }

        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => $ruleEmail,
            'password'      => $rulePassword,
            'repassword'    => $ruleRePassword,
            'role_id'       => $ruleRole,
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        // $idCard = $this->generateID();

        $user = User::find($request->hidden_id);

        if ($request->filled('password')) {
            $password = $request->password;
        } elseif ($request->hidden_id) {
            if ($request->filled('password')) {
                $password = $request->password;
            } else {
                $password = $user->password;
            }
        } else {
            $password = null;
        }

        if ($user) {

            $role_id = $request->role_id;

            $isChanged = false;
            if (
                $user->name !== $request->name ||
                $user->email !== $request->email ||
                (!empty($request->password) && !\Hash::check($request->password, $user->password)) ||
                $user->role_id !== $role_id
            ) {
                $isChanged = true;
            }

            if (!$isChanged) {
                return response()->json(['success' => 'Tidak ada perubahan']);
            }
        } else {
            $role_id = $request->role_id;
        }

        User::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => $password,
                'role_id'   => $role_id,
                // 'id_card'   => $idCard,
            ]
        );

        // IDCard::create(['number' => $idCard]);

        return response()->json(['success' => 'User saved successfully']);
    }

    private function generateID()
    {
        $randomNumber = mt_rand(100000, 999999);
        $idcard = $randomNumber;

        while (IDCard::where('number', $idcard)->exists()) {
            $randomNumber = mt_rand(100000, 999999);
            $idcard = $randomNumber;
        }

        return $idcard;
    }

    public function edit($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success' => 'User deleted successfully']);
    }
}
