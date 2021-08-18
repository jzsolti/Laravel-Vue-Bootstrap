<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Article, App\Models\User;

class ArticlesTest extends DuskTestCase
{
    /**
     * @group article
     */
    public function test_user_article()
    {
        $article = Article::factory()->make();
        $user = User::find(1);



        $this->browse(function (Browser $browser) use ($article, $user ) {

            $browser->visit('/')
                ->clickLink('Login')
                ->waitForText('Login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('form button[type="submit"]')
                ->waitForText('User Account')
                ->visit('/user/articles')
                ->waitForText('My Articles')
                ->clickLink('New article')
                ->waitForText('New article')
                ->press('form button[type="submit"]') // press if button
                ->waitForText('Save')
                ->assertSee('The title field is required')
                ->assertSee('The lead field is required.')
                ->assertSee('The content field is required.')
                ->type('title', $article->title)
                ->type('lead', $article->lead)
                ->type('content', $article->content)
                ->check('ul.labels li:nth-child(1) input')
                ->check('ul.labels li:nth-child(3) input')
                ->press('form button[type="submit"]')
                ->waitForText('Edit article')
                ->refresh()
                ->screenshot('articlerefresh')
                ->waitForText('Save')
                ->assertInputValue('title', $article->title)
                ->assertInputValue('lead', $article->lead)
                ->assertInputValue('content', $article->content)
                ->assertChecked('ul.labels li:nth-child(1) input')
                ->assertChecked('ul.labels li:nth-child(3) input');

            $browser->visit('/user/articles')
                ->waitForText('My Articles')
                ->click('table thead tr th:nth-child(2)')
                ->click('tr:first-child a.btn-link') // click if link
                ->waitForText('Edit article');

            $titleValue = $browser->inputValue('title');

            //->attach('image', storage_path('app/public/testing/test_upload.jpg'))

            $browser->type('title', ' ') // need type method and single space instead of a blank string or clear method
                ->press('form button[type="submit"]')
                ->waitForText('Save')
                ->assertInputValue('title', '')
                ->assertSee('The title field is required.')
                ->type('title', $titleValue . ' updated')
                ->press('form button[type="submit"]')
                ->waitForText('Save')
                ->assertInputValue('title', $titleValue . ' updated')
                ->assertSee('Article updated')
                ->assertSee('Delete');

            $browser->press('Delete')
                ->waitForText('Are you sure?')
                ->assertSee('No')
                ->press('Yes')
                ->waitForText('Article deleted')
                ->assertSee('My Articles');
        });
    }
}
