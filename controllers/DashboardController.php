<?php
/**
 * Dashboard Controller
 */

class DashboardController extends Controller {

    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Index page (Dashboard switch)
    public function index(): void {
        $currentUser = $_SESSION['user'];
        // Fetch detailed profile for current user to compute profile completion
        $profile = $this->userModel->findById((int)$currentUser['id']);
        
        $profileCompletion = $this->userModel->getProfileCompletion($profile);

        if (is_admin()) {
            // Load admin widgets and data
            $stats = $this->userModel->getStatistics();
            $regTrend = $this->userModel->getRegistrationTrend();
            
            $this->view('dashboard/admin', [
                'title'             => 'Admin Dashboard | ' . APP_NAME,
                'stats'             => $stats,
                'regTrend'          => $regTrend,
                'profileCompletion' => $profileCompletion,
                'profile'           => $profile
            ]);
        } else {
            // Load regular user dashboard data
            $this->view('dashboard/user', [
                'title'             => 'User Dashboard | ' . APP_NAME,
                'profileCompletion' => $profileCompletion,
                'profile'           => $profile
            ]);
        }
    }
}
