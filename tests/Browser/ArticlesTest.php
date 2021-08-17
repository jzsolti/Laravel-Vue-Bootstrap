<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Article;

class ArticlesTest extends DuskTestCase
{
    /**
     * @group article
     */
    public function testUserCanCreateArticle()
    {
        $article = Article::factory()->make();

        $this->browse(function (Browser $browser) use ($article) {

            $browser->visit('/')
                ->clickLink('Login')
                ->waitForText('Login')
                ->type('email', 'nyul@mail.hu')
                ->type('password', 'password')
                ->press('form button[type="submit"]')
                ->waitForText('User Account')
                ->visit('/user/articles')
                ->waitForText('My Articles')
                ->clickLink('New article')
                ->waitForText('New article')
                ->press('form button[type="submit"]')
                ->waitForText('Save')
                ->assertSee('The title field is required')
                ->assertSee('The lead field is required.')
                ->assertSee('The content field is required.')->screenshot('articlecreate')
                ->type('title', $article->title)
                ->type('lead', $article->lead)
                ->type('content', $article->content)
                ->press('form button[type="submit"]')
                ->waitForText('Edit article')
                ->assertInputValue('title', $article->title)
                ->assertInputValue('lead', $article->lead)
                ->assertInputValue('content', $article->content);
        });
    }

    /**
     * @group article
     */
    public function testUserCanUpdateArticle()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/')
                ->clickLink('Login')
                ->waitForText('Login')
                ->type('email', 'nyul@mail.hu')
                ->type('password', 'password')
                ->press('form button[type="submit"]')
                ->waitForText('User Account')
                ->visit('/user/articles')
                ->waitForText('My Articles');

            $browser->click('tr:first-child a.btn-link')
                ->waitForText('Edit article');

            $titleValue = $browser->inputValue('title');
            

            $browser->type('title', ' ') // need a single space instead of a blank string or clear method
                ->press('form button[type="submit"]')
                ->waitForText('Save')
                ->assertInputValue('title', '')
                ->assertSee('The title field is required.')
                ->type('title', $titleValue . ' updated')
                ->press('form button[type="submit"]')
                ->waitForText('Save')
                ->assertInputValue('title', $titleValue . ' updated')
                ->assertSee('Article updated');
        });
    }
}
