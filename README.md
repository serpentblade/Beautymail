# Beautymail for Laravel 5

Beautymail makes it super easy to send beatiful responsive HTML emails. It's made for things like:

* Welcome emails
* Password reminders
* Invoices
* Data exports

If you're on Laravel 4, use the `1.x` branch.

## Templates

There are tons of great looking HTML email templates out there. Campaign Monitor and Mailchimp has released hundreds for free. It is pretty simple to adapt a template to Beautymail. If you do, please send a PR.

__Widgets__ by [Campaign Monitor](https://www.campaignmonitor.com/templates/all/):

![Widget Template](screenshots/widgets.png?raw=true "Widgets template")

__Minty__ by __Stamplia__:

![Widget Template](screenshots/minty.png?raw=true "Widgets template")

__Sunny__

![Widget Template](screenshots/sunny.png?raw=true "Sunny template")

## Installation

Add the package to your `composer.json` by running:

    composer require snowfire/beautymail dev-master

When it's installed, add it to the providers list in `config/app.php`

	Snowfire\Beautymail\BeautymailServiceProvider::class,

Publish assets to your public folder

    php artisan vendor:publish

## Send your first Beauty mail

Add this to your `routes.php`

```php
Route::get('/test', function()
{
	$beautymail = app()->make(Snowfire\Beautymail\Beautymail::class);
    $beautymail->send('emails.welcome', [], function($message)
    {
        $message
			->from('bar@example.com')
			->to('foo@example.com', 'John Smith')
			->subject('Welcome!');
    });

});
```

Now create `resources/views/emails/welcome.blade.php`

```html
@extends('beautymail::templates.widgets')

@section('content')

	@startBeautymail('widgets.article')

		<h4 class="secondary"><strong>Hello World</strong></h4>
		<p>This is a test</p>

	@endBeautymail


	@startBeautymail('widgets.newfeature')

		<h4 class="secondary"><strong>Hello World again</strong></h4>
		<p>This is another test</p>

	@endBeautymail

@stop
```

That's it!

## Options

### _Template:_ Widgets

To change colours for the different segments, pass a colour variable:

```php
@startBeatymail('widgets.article', ['color' => '#0000FF'])
```

#### Minty template example

```html
@extends('beautymail::templates.minty')

@section('content')

	@startBeautymail('minty.content')
		<tr>
			<td class="title">
				Welcome Steve
			</td>
		</tr>
		<tr>
			<td width="100%" height="10"></td>
		</tr>
		<tr>
			<td class="paragraph">
				This is a paragraph text
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="title">
				This is a heading
			</td>
		</tr>
		<tr>
			<td width="100%" height="10"></td>
		</tr>
		<tr>
			<td class="paragraph">
				More paragraph text.
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td>
				@beautymail('minty.button', ['text' => 'Sign in', 'link' => '#'])
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
	@endBeautymail

@stop
```

### Ark template example

```html
@extends('beautymail::templates.ark')

@section('content')

    @beautymail('ark.heading', [
		'heading' => 'Hello World!',
		'level' => 'h1'
	])

    @startBeautymail('ark.content')

        <h4 class="secondary"><strong>Hello World</strong></h4>
        <p>This is a test</p>

    @endBeautymail

    @beautymail('ark.heading', [
		'heading' => 'Another headline',
		'level' => 'h2'
	])

    @startBeautymail('ark.content')

        <h4 class="secondary"><strong>Hello World again</strong></h4>
        <p>This is another test</p>

    @endBeautymail

@stop
```

#### Sunny template example

```html
@extends('beautymail::templates.sunny')

@section('content')

    @beautymail ('sunny.heading' , [
        'heading' => 'Hello!',
        'level' => 'h1',
    ])

    @startBeautymail('sunny.content')

        <p>Today will be a great day!</p>

    @endBeautymail

    @beautymail('sunny.button', [
        	'title' => 'Click me',
        	'link' => 'http://google.com'
    ])

@stop
```
