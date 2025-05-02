<?php

use App\Models\Role;

class UserHasRoles {
  public function handle($request, Closure $next, ...$roles) {
    if (auth('api')->user()) {
      // get the rules ids from the roles slugs passed to the middleware
      $rolesIds = [];
      foreach ($roles as $role) {
        $roleId = Role::where('slug', $role)->first();
        if ($roleId) {
          $rolesIds[] = $roleId->id;
        }
      }
      // check if the user has the roleid from the roles slugs passed to the middleware
      $user = auth('api')->user();
      $userRole = $user->role_id;
      if (in_array($userRole, $rolesIds)) {
        return $next($request);
      } else {
        return response()->json(['message' => 'Unauthorized'], 401);
      }
    } else {
      return response()->json(['message' => 'Unauthorized'], 401);
    }
  }
}