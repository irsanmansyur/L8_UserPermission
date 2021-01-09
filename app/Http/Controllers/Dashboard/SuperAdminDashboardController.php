<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\{BaseAdminController, Controller};
use App\Models\Menu;
use App\Models\Navigation;
use App\Models\User;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminDashboardController extends BaseAdminController
{
  public function index()
  {
    return view("Dashboard.super-admin", [
      'roles_count' => Role::count(),
      "permission_count" => Permission::count(),
      "users_count" => User::count(),
      "navigations_count" => Navigation::count(),
      "menus_count" => Menu::whereHas("navigations")->count(),
      "staticticViewThisMonth" => $this->StatictikUserPerDay()
    ]);
  }
  private function StatictikUserPerDay($month = null, $year = null)
  {
    $month = $month ?? date("m");
    $days = getDaysFromMonthAndYear($month, $year);
    foreach ($days as $i => $day) {
      $days[$i] = ["day" => $day, "count" => View::whereDay("viewed_at", $day)->whereViewableType(User::class)->count()];
    }
    return json_encode($days);
  }
}
