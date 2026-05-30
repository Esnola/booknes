<?php

namespace App\Providers\Filament;

use App\Filament\App\Resources\Books\BookResource;
use App\Filament\App\Resources\BookUsers\BookUserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $this->basePanel($panel);

        return $panel
            ->default()
            ->id('app')
            ->path('')
            ->login()
            ->registration()
            ->passwordReset()
            ->profile()
            ->colors([
                'primary' => Color::Lime,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\Filament\App\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\Filament\App\Pages')
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\Filament\App\Widgets')
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make('')
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon('tabler-home')
                                ->url(Dashboard::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.app.pages.dashboard')),
                            ...BookResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('My Shelf')
                        ->items([
                            NavigationItem::make('Requested Books')
                                ->icon('tabler-clock-plus')
                                ->url(BookUserResource::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.my-books.index') && request()->get('tab') === null)
                                ->badge(
                                    fn () => auth()->user()->books()->where('status', 'requested')->count()
                                ),
                            NavigationItem::make('Currently Reading')
                                ->icon('tabler-book')
                                ->url(BookUserResource::getUrl().'?tab=borrowed')
                                ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.my-books.index') && request()->get('tab') === 'borrowed')
                                ->badge(
                                    fn () => auth()->user()->books()->where('status', 'borrowed')->count()
                                ),
                            NavigationItem::make('Past Reads')
                                ->icon('tabler-book-2')
                                ->url(BookUserResource::getUrl().'?tab=returned')
                                ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.my-books.index') && request()->get('tab') === 'returned')
                                ->badge(
                                    fn () => auth()->user()->books()->where('status', 'returned')->count()
                                ),
                        ]),
                ]);
            })
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
