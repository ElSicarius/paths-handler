<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Endpoint;
use App\Models\Log;

class AdminController extends BaseController
{
    private $demoUsername;
    private $demoPassword;

    public function __construct()
    {
        $this->demoUsername = $_ENV['UI_USER'] ?? 'admin';
        $this->demoPassword = $_ENV['UI_password'] ?? 'YeahItsInClearWhatYouGonnaDo';
    }

    // ---------- Auth ----------
    public function showLoginForm(Request $request)
    {
        return view('admin.login');
    }

    public function processLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($username === $this->demoUsername && $password === $this->demoPassword) {
            $_SESSION['logged_in'] = true;
            return redirect($request->getBaseUrl().'/'.$request->path().'/../');
        } else {
            return response('Invalid credentials', 401);
        }
    }

    public function logout(Request $request)
    {
        session_destroy();
        return redirect($request->getBaseUrl().'/'.$request->path().'/../login');
    }

    // -------- PAGE D’ACCUEIL ADMIN --------
    public function index(Request $request)
    {
        $adminPath = env('ADMIN_PATH', 'admin');
        return view('admin.home', ['adminPath' => $adminPath]);
    }

    public function clearAllLogs()
    {
        \App\Models\Log::truncate();
        $adminPath = env('ADMIN_PATH', 'admin');
        return redirect('/'.$adminPath.'/logs');
    }


    // ============ ENDPOINTS ===============
    /**
     * Liste des endpoints
     */
    public function listEndpoints(Request $request)
    {
        $endpoints = Endpoint::orderBy('id', 'asc')->get();
        // On passe la liste à la vue
        return view('admin.endpoints-list', [
            'endpoints' => $endpoints
        ]);
    }

    /**
     * Page d'édition /admin-xxx/endpoints/{id}/edit
     */
    public function editEndpoint($id)
    {
        $endpoint = Endpoint::findOrFail($id);
        $adminPath = env('ADMIN_PATH', 'admin');
        return view('admin.endpoints-edit', [
            'endpoint' => $endpoint,
            'adminPath' => $adminPath
        ]);
    }

    /**
     * CREATE
     */
    public function createEndpoint(Request $request)
    {
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        Endpoint::create($data);
        // retour sur la liste
        $adminPath = env('ADMIN_PATH', 'admin');
        return redirect('/'.$adminPath.'/endpoints');
    }

    /**
     * UPDATE
     */
    public function updateEndpoint($id, Request $request)
    {
        $endpoint = Endpoint::findOrFail($id);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $endpoint->fill($data);
        $endpoint->save();

        // retour sur la liste
        $adminPath = env('ADMIN_PATH', 'admin');
        return redirect('/'.$adminPath.'/endpoints');
    }

    /**
     * DELETE
     */
    public function deleteEndpoint($id, Request $request)
    {
        $endpoint = Endpoint::findOrFail($id);
        $endpoint->delete();
        return redirect($request->getBaseUrl().'/'.$request->path().'/../');
    }

    // -------- LOGS --------
    public function showLogs(Request $request)
    {
        $page = max(1, (int)$request->query('page', 1));
        $perPage = 20;
        $total = Log::count();
        $adminPath = env('ADMIN_PATH', 'admin');

        $logs = Log::orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return view('admin.logs', [
            'logs' => $logs,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'adminPath' => $adminPath
        ]);
    }
}
