<?php

namespace Tests\Feature;

use App\Models\Profession;
use Tests\TestCase;

class ProfessionCrudTest extends TestCase
{
    public function test_operator_cannot_access_professions(): void
    {
        $this->actingAsOperator();
        $this->get(route('professions.index'))->assertForbidden();
    }

    public function test_admin_can_view_index(): void
    {
        $this->actingAsAdmin();
        Profession::create(['name_uz' => 'Elektrogazpayvandchi']);

        $this->get(route('professions.index'))
            ->assertOk()
            ->assertSee('Elektrogazpayvandchi');
    }

    public function test_admin_can_create_profession(): void
    {
        $this->actingAsAdmin();

        $this->post(route('professions.store'), [
            'code'    => 'P-001',
            'name_uz' => 'Test kasbi',
            'name_oz' => 'Тест касби',
            'name_ru' => 'Тестовая профессия',
            'name_en' => 'Test profession',
        ])->assertRedirect(route('professions.index'));

        $this->assertDatabaseHas('professions', [
            'code'    => 'P-001',
            'name_uz' => 'Test kasbi',
        ]);
    }

    public function test_validation_requires_name_uz(): void
    {
        $this->actingAsAdmin();

        $this->post(route('professions.store'), ['code' => 'P-002'])
            ->assertSessionHasErrors('name_uz');
    }

    public function test_admin_can_update_profession(): void
    {
        $this->actingAsAdmin();
        $p = Profession::create(['name_uz' => 'Eski nom']);

        $this->put(route('professions.update', $p), [
            'name_uz' => 'Yangi nom',
            'name_ru' => 'Новое имя',
        ])->assertRedirect(route('professions.index'));

        $this->assertDatabaseHas('professions', ['id' => $p->id, 'name_uz' => 'Yangi nom']);
    }

    public function test_admin_can_delete_profession(): void
    {
        $this->actingAsAdmin();
        $p = Profession::create(['name_uz' => 'O\'chiriladigan']);

        $this->delete(route('professions.destroy', $p))
            ->assertRedirect(route('professions.index'));

        $this->assertDatabaseMissing('professions', ['id' => $p->id]);
    }

    public function test_search_filters_by_name(): void
    {
        $this->actingAsAdmin();
        Profession::create(['name_uz' => 'Elektrogazpayvandchi']);
        Profession::create(['name_uz' => 'Montajchi']);

        $this->get(route('professions.index', ['q' => 'Montaj']))
            ->assertOk()
            ->assertSee('Montajchi')
            ->assertDontSee('Elektrogazpayvandchi');
    }
}