<?php

namespace Tests\Feature;

  use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books () {
        $books = Book::factory(4)->create();

         $this->getJson(route('books.index'))
        ->assertJsonFragment( [
            'title' => $books[0]->title,
        ]);   
    }

    /** @test */
    function can_get_one_book() {
        $book = Book::factory(1)->create();
  

         $this->getJson( route('books.show', $book[0]) ) 
         ->assertJsonFragment( [
            'title' => $book[0]->title,
        ]); 
    }

    /** @test */
    function can_create_book () {

        $this->postJson( route('books.store'),[])
                ->assertJsonValidationErrorFor('title');

        $this->postJson( route('books.store'),[
            'title' => 'nuevo libro'
        ] )->assertJsonFragment( [
            'title' => 'nuevo libro'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'nuevo libro'
        ]);
    }

     /** @test */

     function can_update_book() {
        $book = Book::factory(1)->create();

        $this->patchJson( route('books.update', $book[0]),[])
        ->assertJsonValidationErrorFor('title');

        $response = $this->patchJson( route('books.update', $book[0]), [
            'title' => 'nuevo libro editado' 
        ])->assertJsonFragment( [
            'title' =>  'nuevo libro editado',
        ]);
     
        $this->assertDatabaseHas('books', [
            'title' =>  'nuevo libro editado',
        ]);
    }
     /** @test */
     function can_delete_book () {
        $book = Book::factory()->create();
        $this->deleteJson ( route ('books.destroy', $book))
        ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
         
     }
}
