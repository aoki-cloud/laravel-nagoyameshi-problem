<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */

    /*会員一覧ページ*/
    /*未ログインのユーザーは管理者側の会員一覧ページにアクセスできない*/
    public function test_guest_cannot_access_admin_users_index()
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /*ログイン済みの一般ユーザーは管理者側の会員一覧ページにアクセスできない*/
    public function test_user_cannnot_access_admin_users_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /*ログイン済みの管理者は管理者側の会員一覧ページにアクセスできる*/
    public function test_adminUser_can_access_admin_users_index()
    {
        $adminUser = admin::factory()->create();

        $admin = new Admin();
        $adminUser->email = 'admin@example.com';
        $adminUser->password = Hash::make('nagoyameshi');
        $adminUser->save();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    /*会員詳細ページ*/
    /*未ログインのユーザーは管理者側の会員詳細ページにアクセスできない*/
    public function test_guest_cannnot_access_admin_users_show()
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.show', $user));

        $response->assertRedirect(route('admin.login'));
    }

    /*ログイン済みの一般ユーザーは管理者側の会員詳細ページにアクセスできない*/
    public function test_adminUser_cannnot_access_admin_users_show()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.show', $user));

        $response->assertRedirect(route('admin.login'));
    }

    /*ログイン済みの管理者は管理者側の会員詳細ページにアクセスできる*/
    public function test_adminUser_can_access_admin_usrs_show()
    {
        $user = User::factory()->create();

        $adminUser = new Admin();
        $adminUser->email = 'admin@example.com';
        $adminUser->password = Hash::make('nagoyameshi');
        $adminUser->save();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.users.show', $user));

        $response->assertStatus(200);
    }
}
