<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Comment::insert([
            [
                'id' => 5,
                'post_id' => 4,
                'nama_komentator' => 'John Doe',
                'isi_komentar' => 'Artikel yang sangat menarik dan informatif!',
                'created_at' => '2025-08-20 18:38:20',
                'updated_at' => '2025-08-20 18:38:20',
            ],
            [
                'id' => 7,
                'post_id' => 5,
                'nama_komentator' => 'Agnan',
                'isi_komentar' => 'Nice Insight',
                'created_at' => '2025-08-20 18:42:44',
                'updated_at' => '2025-08-20 18:42:44',
            ],
            [
                'id' => 8,
                'post_id' => 5,
                'nama_komentator' => 'step',
                'isi_komentar' => 'nice info',
                'created_at' => '2025-08-21 00:56:41',
                'updated_at' => '2025-08-21 00:56:41',
            ],
            [
                'id' => 10,
                'post_id' => 9,
                'nama_komentator' => 'Windah Basudara',
                'isi_komentar' => 'Mantap, menambah wawasan baru',
                'created_at' => '2025-08-21 11:16:53',
                'updated_at' => '2025-08-21 11:16:53',
            ],
            [
                'id' => 11,
                'post_id' => 8,
                'nama_komentator' => 'Vinicius Junior',
                'isi_komentar' => 'agree',
                'created_at' => '2025-08-21 11:18:44',
                'updated_at' => '2025-08-21 11:18:44',
            ],
            [
                'id' => 15,
                'post_id' => 10,
                'nama_komentator' => 'Fahri',
                'isi_komentar' => 'mantap fiturnya sangat membantu',
                'created_at' => '2025-08-21 16:28:58',
                'updated_at' => '2025-08-21 16:28:58',
            ],
        ]);
    }
}
