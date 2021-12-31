<?php

namespace Tests\Feature;


use Tests\TestCase;

class FileUploadTest extends testcase
{

    /** @test */
    public function it_will_get_403_if_morph_model_is_not_found()
    {
        $this->post('/api/files',[
            'id' => 100,
            'model' => 'notamodel',
        ])->assertStatus(403);
    }
}