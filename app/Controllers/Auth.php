<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\SiswaModel;

class Auth extends BaseController
{

    protected $AdminModel;
    protected $SiswaModel;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->AdminModel = new AdminModel();
        $this->SiswaModel = new SiswaModel();
    }


    public function index($login_registre = '', $login_create = 'none')
    {
        // Data login dan signup Jika terjadi eror pada saat sign up
        $data['login_registre'] = $login_registre;
        $data['login_create'] = $login_create;
        $data['admin'] = $this->AdminModel->asObject()->findAll();

        return view('auth/index', $data);
    }

    public function login()
    {
        // Data Admin Dicari berdasarkan email
        $user = $this->AdminModel->getByEmail($this->request->getVar('username'));
        // Jika Data Admin ada maka lanjut ke verifikasi password
        // Jika data admin tidak ada maka akan mencari data siswa
        // artinya, jika bukan admin yg sedang login, berarti siswa yg sedang login
        if ($user != null) {

            // MEngecek Password
            if (password_verify($this->request->getVar('password'), $user->password)) {

                // Jika Password Benar Maka siapkan data session dan arahkan kehalaman admin
                $data_session = [
                    'email' => $user->email,
                    'nama' => $user->nama_admin,
                    'role' => $user->role
                ];
                session()->set($data_session);
                return redirect()->to('admin');

                // Jika Password salah maka siapkan alert salah dan arahkan ke halaman login
            } else {
                session()->setFlashdata(
                    'pesan',
                    '<script>
                        Swal.fire(
                        "Oopss!",
                        "Wrong Password!",
                        "error"
                        )
                    </script>'
                );
                return redirect()->to('auth');
            }
        } else {

            // Mengambil data siswa berdasarkan email / NIM yang di inputkan di form login
            $user = $this->SiswaModel->getByEmail($this->request->getVar('username'));
            if ($user == null) {
                $user = $this->SiswaModel->getByNim($this->request->getVar('username'));
            }

            // Mengecek Jika data siswanya ada maka lanjut ke verifikasi passsword
            if ($user != null) {
                // Pengecekan password
                // Jika Password ada maka siapkan data session dan alihkan kehalaman siswa
                if (password_verify($this->request->getVar('password'), $user->password)) {
                    $data_session = [
                        'email' => $user->email,
                        'nama' => $user->nama_siswa,
                        'role' => $user->role,
                        'nim' => $user->nim
                    ];
                    session()->set($data_session);
                    return redirect()->to('students');

                    // Jika Password salah maka siapkan alert salah dan alihkan kehalaman login
                } else {
                    session()->setFlashdata(
                        'pesan',
                        '<script>
                            Swal.fire(
                            "Oopss!",
                            "Wrong Password!",
                            "error"
                            )
                        </script>'
                    );
                    return redirect()->to('auth');
                }
            } else {
                // Jika data siswanya tidak ada maka siapkan alert data tidak ada dan alihkan kehalaman login
                session()->setFlashdata(
                    'pesan',
                    '<script>
                        Swal.fire(
                        "Oopss!",
                        "Akun Tidak Ada!",
                        "error"
                        )
                    </script>'
                );
                return redirect()->to('auth');
            }
        }
    }

    public function registration()
    {
        // Cek apakah email yang dimasukan sudah terdaftar di database
        // jika iyaa maka siapkan pesan error dan alihkan kehalaman login
        if ($this->AdminModel->getByEmail($this->request->getVar('email'))) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Oops..! ',
                    'Email Sudah Dipakai',
                    'error'
                    )
            </script>"
            );
            return redirect()->to('auth');
        }

        // Siapkan Data Untuk Dimasukan Kedalam Database
        $data = [
            'nama_admin' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role' => 1,
            'date_created' => time(),
            'is_active' => 1,
            'gambar' => "default.jpg"
        ];

        $this->AdminModel->save($data);
        session()->setFlashdata(
            'pesan',
            "<script>
                Swal.fire(
                'Berhasil!',
                'Akun Admin sudah dibuat',
                'success'
                )
        	</script>"
        );
        return redirect()->to('auth');
    }
    public function logout()
    {
        session()->destroy();
        session()->setFlashdata(
            'pesan',
            "<script>
                    Swal.fire(
                    'Berhasil',
                    'Kamu sudah logout',
                    'success'
                    )
            </script>"
        );
        return redirect()->to('auth');
    }
}
