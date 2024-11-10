<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*会員一覧ページ*/
    public function index(Request $request) {
        $keyword = $request->input('keyword');
        $query = User::query();

        // クエリビルダを使って検索とページネーションを行う
        if (!empty($keyword)) {
            $query->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('furigana', 'LIKE', "%{$keyword}%");
        }

        // ページネーションを適用した結果を取得
        $users = $query->paginate(10); // 1ページあたりの表示数を設定

        // 総数を取得
        $total = $users->total();
        // ビューに変数を渡して返します
        return view('users.index', compact('users', 'keyword', 'total'));
    }

    /*会員詳細ページ*/
    public function show(User $user)
    {
        return view('admin.users.show', [
            'user' => $user,
        ]);
    }
}
