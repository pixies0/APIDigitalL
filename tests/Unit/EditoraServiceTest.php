<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\EditoraService;
use App\Repositories\EditoraRepository;
// Removendo 'use Mockery;' - não precisaremos mais dele
use App\Exceptions\AppError; // Certifique-se de importar sua classe de exceção personalizada

class EditoraServiceTest extends TestCase
{

    public function test_create_editora_calls_repository_with_correct_data()
    {

        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject $mockRepository */
        $mockRepository = $this->createMock(EditoraRepository::class);



        $mockRepository->expects($this->once())
            ->method('create')
            ->with([
                'nome' => 'Editora Teste',
                'endereco' => 'Rua Teste, 123',
                'telefone' => '11987654321'
            ])
            ->willReturn((object)[ // Simula um objeto Editora retornado do DB
                'id' => 1,
                'nome' => 'Editora Teste',
                'endereco' => 'Rua Teste, 123',
                'telefone' => '11987654321'
            ]);

        $service = new EditoraService($mockRepository);

        $data = [
            'nome' => 'Editora Teste',
            'endereco' => 'Rua Teste, 123',
            'telefone' => '11987654321'
        ];

        $result = $service->createEditora($data);

        $this->assertIsObject($result);
        $this->assertEquals('Editora Teste', $result->nome);
        $this->assertObjectHasProperty('id', $result);
    }

    public function test_create_editora_with_invalid_data_throws_exception()
    {

        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject $mockRepository */
        $mockRepository = $this->createMock(EditoraRepository::class);

        $service = new EditoraService($mockRepository);

        $invalidData = ['nome' => ''];

        $this->expectException(AppError::class);
        $this->expectExceptionMessage('O nome da editora é obrigatório.');
        $this->expectExceptionCode(400);

        $service->createEditora($invalidData);
    }

    public function test_get_editora_by_id_calls_repository_with_correct_key()
    {
        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject $mockRepository */
        $mockRepository = $this->createMock(EditoraRepository::class);
        $editoraId = 1;
        $expectedEditora = (object)[
            'id' => $editoraId,
            'nome' => 'Editora ABC',
            'endereco' => 'Endereço Teste',
            'telefone' => '999999999'
        ];

        // Esperamos que findByKey seja chamado com os argumentos específicos
        $mockRepository->expects($this->once())
            ->method('findByKey')
            ->with('id', $editoraId, [], [], null, true)
            ->willReturn($expectedEditora);

        $service = new EditoraService($mockRepository);

        $result = $service->getEditoraById($editoraId);

        $this->assertEquals($expectedEditora, $result);
    }

    public function test_get_editora_by_id_returns_null_if_not_found()
    {
        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject $mockRepository */
        $mockRepository = $this->createMock(EditoraRepository::class);
        $editoraId = 99999999; // Um ID que não existe


        $mockRepository->expects($this->once())
            ->method('findByKey')
            ->with('id', $editoraId, [], [], null, true)
            ->willReturn(null);

        $service = new EditoraService($mockRepository);

        $result = $service->getEditoraById($editoraId);

        $this->assertNull($result);
    }

    public function test_update_editora_updates_existing_editora()
    {
        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject $mockRepository */
        $mockRepository = $this->createMock(EditoraRepository::class);
        $editoraId = 1;
        $editora = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['fill', 'save'])
            ->getMock();

        $editora->id = $editoraId;
        $editora->nome = 'Editora Antiga';
        $editora->endereco = 'Endereço Antigo';
        $editora->telefone = '999999999';

        $editora->expects($this->once())
            ->method('fill')
            ->with([
                'nome' => 'Editora Atualizada',
                'endereco' => 'Endereço Atualizado',
                'telefone' => '888888888'
            ])
            ->willReturnSelf();

        $editora->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $mockRepository->expects($this->once())
            ->method('findByKey')
            ->with('id', $editoraId, [], [], null, true)
            ->willReturn($editora);

        $service = new EditoraService($mockRepository);

        $result = $service->updateEditora($editoraId, [
            'nome' => 'Editora Atualizada',
            'endereco' => 'Endereço Atualizado',
            'telefone' => '888888888'
        ]);

        $this->assertEquals($editora, $result);
    }

    public function test_delete_editora_deletes_existing_editora()
    {
        $editoraId = 1;

        $editora = (object)[
            'id' => $editoraId,
            'nome' => 'Editora Teste',
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999'
        ];

        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject $mockRepository */
        $mockRepository = $this->createMock(EditoraRepository::class);

        $mockRepository->expects($this->once())
            ->method('findByKey')
            ->with('id', $editoraId, [], [], null, true)
            ->willReturn($editora);

        $mockRepository->expects($this->once())
            ->method('delete')
            ->with($editoraId)
            ->willReturn(true);

        $service = new EditoraService($mockRepository);

        $result = $service->deleteEditora($editoraId);

        $this->assertTrue($result);
    }

    public function test_delete_editora_throws_exception_if_not_found()
    {
        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject $mockRepository */
        $mockRepository = $this->createMock(EditoraRepository::class);
        $editoraId = 999;

        $mockRepository->expects($this->once())
            ->method('findByKey')
            ->with('id', $editoraId, [], [], null, true)
            ->willReturn(null);

        $service = new EditoraService($mockRepository);

        $this->expectException(AppError::class);
        $this->expectExceptionMessage('Editora não encontrada.');
        $this->expectExceptionCode(404);

        $service->deleteEditora($editoraId);
    }

    public function test_get_all_editoras_returns_collection()
    {
        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject $mockRepository */
        $mockRepository = $this->createMock(EditoraRepository::class);

        $mockEditoras = collect([
            (object)['id' => 1, 'nome' => 'Editora 1'],
            (object)['id' => 2, 'nome' => 'Editora 2'],
        ]);

        $mockRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($mockEditoras);

        $service = new EditoraService($mockRepository);

        $result = $service->getAllEditoras();

        $this->assertCount(2, $result);
        $this->assertEquals('Editora 1', $result[0]->nome);
    }

    public function test_search_editoras_by_name_calls_repository_correctly()
    {
        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject */
        $mockRepository = $this->createMock(EditoraRepository::class);
        $searchName = 'Teste';

        $mockResult = collect([
            (object)['id' => 1, 'nome' => 'Editora Teste']
        ]);

        $mockRepository->expects($this->once())
            ->method('findByKey')
            ->with('nome', $searchName, [], [], null, false)
            ->willReturn($mockResult);

        $service = new EditoraService($mockRepository);

        $result = $service->searchEditorasByName($searchName);

        $this->assertEquals($mockResult, $result);
    }

    public function test_editora_exists_returns_true_or_false()
    {
        $editoraId = 1;

        $mockModel = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['where', 'exists'])
            ->getMock();

        $mockModel->expects($this->once())
            ->method('where')
            ->with('id', $editoraId)
            ->willReturnSelf();

        $mockModel->expects($this->once())
            ->method('exists')
            ->willReturn(true);

        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject */
        $mockRepository = $this->createMock(EditoraRepository::class);
        $mockRepository->method('getModel')->willReturn($mockModel);

        $service = new EditoraService($mockRepository);

        $this->assertTrue($service->editoraExists($editoraId));
    }

    public function test_editora_name_exists_returns_true_or_false()
    {
        $nome = 'Editora Exemplo';

        $mockModel = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['where', 'exists'])
            ->getMock();

        $mockModel->expects($this->once())
            ->method('where')
            ->with('nome', $nome)
            ->willReturnSelf();

        $mockModel->expects($this->once())
            ->method('exists')
            ->willReturn(true);

        /** @var \App\Repositories\EditoraRepository&\PHPUnit\Framework\MockObject\MockObject */
        $mockRepository = $this->createMock(EditoraRepository::class);
        $mockRepository->method('getModel')->willReturn($mockModel);

        $service = new EditoraService($mockRepository);

        $this->assertTrue($service->editoraNameExists($nome));
    }
}
