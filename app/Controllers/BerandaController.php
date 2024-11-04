<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RapatModel;
use App\Models\RoleRapatModel;
use App\Models\AbsensiModel;
use App\Models\TranskripsiRapatModel;


class BerandaController extends BaseController
{

    protected $TranskripsiRapatModel;

    public function __construct()
    {
        // Load the TranskripsiRapatModel
        $this->TranskripsiRapatModel = new TranskripsiRapatModel();
    }
    public function index()
    {
        // Load models
        $rapatModel = new RapatModel();
        $transkripsiModel = new TranskripsiRapatModel();
        $userModel = new UserModel();
        $roleRapatModel = new RoleRapatModel();
        $absensiModel = new AbsensiModel();

        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login'); // Redirect to login if not logged in
        }

        // Get the logged-in user's ID
        $userId = session()->get('user_id');

        // Fetch meetings
        $rapatList = $rapatModel->findAll();

        // Fetch transcriptions
        $transcriptions = [];
        foreach ($rapatList as $rapat) {
            $transcription = $transkripsiModel->where('id_rapat', $rapat['id'])->first();
            if ($transcription) {
                $transcriptions[$rapat['id']] = $transcription['transkripsi']; // Use 'transkripsi' field
            } else {
                $transcriptions[$rapat['id']] = null; // No transcription available
            }
        }

        // Prepare user data
        $user = $userModel->find($userId);

        // Initialize user role variable
        $userRole = ''; // Default in case no role is found

        // Check if the user has a specific role in any meeting
        $userRoleData = $roleRapatModel->where('id_users', $userId)->first();
        if ($userRoleData) {
            switch ($userRoleData['id_roles']) {
                case 1:
                    $userRole = 'ketua';
                    break;
                case 2:
                    $userRole = 'notulen';
                    break;
                case 3:
                    $userRole = 'anggota';
                    break;
                default:
                    $userRole = 'unknown';
            }
        }

        // Fetch roles and user names
        $roles = [];
        foreach ($rapatList as $rapat) {
            $roles[$rapat['id']] = $roleRapatModel->where('id_rapat', $rapat['id'])->findAll();
        }

        // Prepare user names array
        $userNames = [];
        foreach ($userModel->findAll() as $userRecord) {
            $userNames[$userRecord['id']] = $userRecord['nama'];
        }

        // Calculate the number of meetings attended by the user
        $jumlah_rapat = [
            'ketua' => 0,
            'notulensi' => 0,
            'anggota' => 0,
        ];

        foreach ($roles as $role) {
            foreach ($role as $item) {
                if ($item['id_users'] == $userId) {
                    switch ($item['id_roles']) {
                        case 1:
                            $jumlah_rapat['ketua']++;
                            break;
                        case 2:
                            $jumlah_rapat['notulensi']++;
                            break;
                        case 3:
                            $jumlah_rapat['anggota']++;
                            break;
                    }
                }
            }
        }

        // Prepare attendance status for each meeting
        $has_attended = [];
        foreach ($rapatList as $rapat) {
            $attendanceRecord = $absensiModel->where([
                'id_rapat' => $rapat['id'],
                'id_user' => $userId,
                'status_kehadiran' => 'hadir'
            ])->first();
            $has_attended[$rapat['id']] = $attendanceRecord ? true : false; // True if attended, false otherwise
        }

        // Prepare the data for the view, including userId
        $data = [
            'user' => $user,
            'rapats' => $rapatList, // Keep the variable name as 'rapats'
            'transcriptions' => $transcriptions,
            'userRole' => $userRole,
            'roles' => $roles,
            'userNames' => $userNames,
            'jumlah_rapat' => $jumlah_rapat,
            'has_attended' => $has_attended,
            'userId' => $userId, // Pass userId to the view
        ];

        // Return the view for beranda
        return view('beranda', $data);
    }


    // BerandaController
    public function absenHadir($idRapat)
    {
        $userId = session()->get('user_id');
        $absensiModel = new \App\Models\AbsensiModel();

        // Check if the user is already marked present
        if (!$absensiModel->where(['id_rapat' => $idRapat, 'id_user' => $userId])->first()) {
            $absensiModel->save([
                'id_rapat' => $idRapat,
                'id_user' => $userId,
                'status_kehadiran' => 'hadir'
            ]);
        }
        return redirect()->to('/beranda')->with('message', 'Attendance recorded');
    }
    protected function hasAccessToMeeting($meetingId, $roleId)
    {
        // Check if user is authenticated
        if (!session()->get('isLoggedIn')) {
            return false; // User is not logged in
        }

        $userId = session()->get('userId'); // Adjust according to your session variable

        // Check if the user has the specified role in the meeting
        $roles = $this->getRolesForMeeting($meetingId); // Assume you have a method to get roles for the meeting
        return isset($roles[$roleId]) && in_array($userId, array_column($roles[$roleId], 'id_users'));
    }

    protected function getRolesForMeeting($meetingId)
    {
        $roleModel = new RoleRapatModel();
        return $roleModel->where('id_rapat', $meetingId)->findAll();
    }

    public function attendanceList($meetingId)
    {
        // Ensure the user has the notulen role and belongs to the meeting
        if ($this->hasAccessToMeeting($meetingId, 2)) {
            // Retrieve attendance data for the specified meeting
            $absenModel = new AbsensiModel();
            $attendanceData = $absenModel->where('id_rapat', $meetingId)->findAll();

            // Check if attendance data exists
            if (empty($attendanceData)) {
                return view('attendance_list', ['attendanceData' => [], 'message' => 'No attendance data found.']);
            }

            // Get user details for each attendance record
            $userModel = new UserModel();
            foreach ($attendanceData as &$attendance) {
                if (isset($attendance['id_users'])) {
                    $user = $userModel->find($attendance['id_users']);
                    $attendance['nama'] = $user ? $user['nama'] : 'Unknown User';
                } else {
                    $attendance['nama'] = 'No User ID';
                }
            }

            // Pass the attendance data to the view
            return view('attendance_list', ['attendanceData' => $attendanceData]);
        } else {
            // Redirect or show error if the user does not have access
            return redirect()->to('/beranda')->with('error', 'You do not have access to view this attendance.');
        }
    }
    public function getAttendance($meetingId)
    {
        $absenModel = new AbsensiModel();
        // Fetch attendance data for the specified meeting
        $attendanceData = $absenModel->where('id_rapat', $meetingId)->findAll();

        if (empty($attendanceData)) {
            return $this->response->setJSON(['error' => 'No attendance records found.']);
        }

        $userModel = new UserModel();
        foreach ($attendanceData as &$attendance) {
            // Ensure 'id_user' matches your database column
            $user = $userModel->find($attendance['id_user']);
            $attendance['nama'] = $user ? $user['nama'] : 'Unknown User';
        }

        return $this->response->setJSON($attendanceData); // Return data as JSON
    }

    private function getUserRoleData($userId)
    {
        $roleRapatModel = new \App\Models\RoleRapatModel();

        // Query to get role data for the specific user
        return $roleRapatModel->where('user_id', $userId)->first();
    }

    public function transcription($meetingId)
    {
        // Get the logged-in user's ID from the session
        $userId = session()->get('user_id');

        // Load RapatModel to retrieve meeting details
        $rapatModel = new \App\Models\RapatModel();
        $rapat = $rapatModel->find($meetingId);

        // Check if the meeting exists
        if (!$rapat) {
            return redirect()->to('/beranda')->with('error', 'Meeting not found');
        }

        // Load transcription model to retrieve transcription data
        $transcriptionModel = new \App\Models\TranskripsiRapatModel();
        $transcription = $transcriptionModel->where('id_rapat', $meetingId)->first();

        // Retrieve user's role
        $userRoleData = $this->getUserRoleData($userId);
        if (!$userRoleData || $userRoleData['id_roles'] != 2) {
            return redirect()->to('/beranda')->with('error', 'Not authorized');
        }

        // Pass data to the view, including the $rapat variable
        return view('transcription_view', [
            'rapat' => $rapat, // Pass the rapat data to the view
            'transcription' => $transcription,
            'idRapat' => $meetingId
        ]);
    }




    public function saveTranscription($idRapat)
    {
        $transkripsiModel = new TranskripsiRapatModel();

        // Get the transcription data from the POST request
        $data = [
            'id_rapat' => $idRapat,
            'transkripsi' => $this->request->getPost('transkripsi'), // Ensure you are getting the right key
        ];

        // Save the transcription to the database
        if ($transkripsiModel->insert($data)) {
            return redirect()->to('/beranda')->with('success', 'Transkripsi berhasil disimpan.');
        } else {
            return redirect()->to('/beranda')->with('error', 'Gagal menyimpan transkripsi.');
        }
    }




    public function transcribe($meetingId)
    {
        // Only allow notulen role to access this page
        $userId = session()->get('user_id');
        $roleRapatModel = new RoleRapatModel();
        $userRole = $roleRapatModel->where('id_rapat', $meetingId)
            ->where('id_users', $userId)
            ->where('id_roles', 2) // 2 represents notulen
            ->first();

        if ($userRole) {
            $data['meetingId'] = $meetingId;
            return view('transcribe', $data); // Display the transcription view
        } else {
            return redirect()->to('/beranda')->with('error', 'You do not have access to transcribe this meeting.');
        }
    }
    public function viewTranscription($meetingId)
    {
        // Load RapatModel and TranskripsiRapatModel
        $rapatModel = new \App\Models\RapatModel();
        $transcriptionModel = new \App\Models\TranskripsiRapatModel();

        // Fetch meeting details
        $rapat = $rapatModel->find($meetingId);
        if (!$rapat) {
            return redirect()->to('/beranda')->with('error', 'Rapat tidak ditemukan.');
        }

        // Fetch transcription details
        $transcription = $transcriptionModel->where('id_rapat', $meetingId)->first();

        // Pass data to the view
        return view('transcription_view', [
            'rapat' => $rapat,
            'transcription' => $transcription,
            'idRapat' => $meetingId
        ]);
    }

    public function transcriptionView($meetingId)
    {
        $userId = session()->get('user_id');

        // Check if user has the 'notulen' role for the meeting
        $roleRapatModel = new RoleRapatModel();
        $userRoleData = $roleRapatModel->where('id_rapat', $meetingId)
            ->where('id_users', $userId)
            ->first();

        if (!$userRoleData || $userRoleData['id_roles'] != 2) {
            return redirect()->to('/beranda')->with('error', 'Not authorized');
        }

        // Load meeting data
        $rapatModel = new RapatModel();
        $rapat = $rapatModel->find($meetingId);

        // Load transcription data
        $transcriptionModel = new TranskripsiRapatModel();
        $transcription = $transcriptionModel->where('id_rapat', $meetingId)->first();

        // Pass the meeting and transcription data to the view
        return view('transcription_view', [
            'rapat' => $rapat, // Ensure this variable is set
            'transcription' => $transcription,
            'idRapat' => $meetingId // Pass the meeting ID for saving transcriptions
        ]);
    }
}
