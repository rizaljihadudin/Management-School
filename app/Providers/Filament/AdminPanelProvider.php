<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Filament\Resources\AdjacencyResource;
use App\Filament\Resources\CategoryNilaiResource;
use App\Filament\Resources\ClassroomResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\NilaiResource;
use App\Filament\Resources\PeriodeResource;
use App\Filament\Resources\StudentHasClassResource;
use App\Filament\Resources\StudentResource;
use App\Filament\Resources\StudentResource\Widgets\StatsOverview;
use App\Filament\Resources\StudentResource\Widgets\StudentOverview;
use App\Filament\Resources\SubjectResource;
use App\Filament\Resources\TeacherResource;
use App\Filament\Resources\UserResource;
use App\Models\Adjacency;
use App\Models\Periode;
use App\Models\Team;
use Filament\Facades\Filament;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarFullyCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('img/logo/mss-logo.png'))
            ->brandLogoHeight('3rem')
            ->font('Inter', provider: GoogleFontProvider::class)
            ->favicon(asset('img/logo/mss-logo.png'))
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            // ->userMenuItems([
            //     MenuItem::make()
            //         ->label('Settings')
            //         ->url(fn (): string => PeriodeResource::getUrl())
            //         ->icon('heroicon-o-cog-6-tooth'),
            // ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                StatsOverview::class,
                //Widgets\FilamentInfoWidget::class,
            ])
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
            ])
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            // ->tenant(Team::class)
            // ->tenantRegistration(RegisterTeam::class)
            // ->tenantProfile(EditTeamProfile::class)

            #for navigation group
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn (): string => Dashboard::getUrl()),
                            NavigationItem::make('Nilai')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.nilai.index'))
                                ->url(fn (): string => NilaiResource::getUrl()),
                            NavigationItem::make('Adjacency')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.adjacency.index'))
                                ->url(fn (): string => AdjacencyResource::getUrl()),
                        ]),
                    NavigationGroup::make('Academic')
                        ->items([
                            ...TeacherResource::getNavigationItems(),
                            ...StudentResource::getNavigationItems(),
                            ...StudentHasClassResource::getNavigationItems(),
                            ...SubjectResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Source')
                        ->items([
                            ...CategoryNilaiResource::getNavigationItems(),
                            ...ClassroomResource::getNavigationItems(),
                            ...DepartmentResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Setting')
                        ->items([
                            ...PeriodeResource::getNavigationItems(),
                            NavigationItem::make('Roles')
                                ->icon('heroicon-o-user-group')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.roles.index',
                                    'filament.admin.resources.roles.create',
                                    'filament.admin.resources.roles.edit',
                                    'filament.admin.resources.roles.view',
                                ]))
                                ->url(fn (): string => '/admin/roles')
                                ->visible(fn(): bool => auth()->user()->hasRole('admin')),
                            NavigationItem::make('Permissions')
                                ->icon('heroicon-o-lock-closed')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.permissions.index',
                                    'filament.admin.resources.permissions.create',
                                    'filament.admin.resources.permissions.edit',
                                    'filament.admin.resources.permissions.view',
                                ]))
                                ->url(fn (): string => '/admin/permissions')
                                ->visible(fn(): bool => auth()->user()->hasRole('admin')),
                            ...UserResource::getNavigationItems(),
                        ]),
                ]);
            })
            ->databaseNotifications()
            ->viteTheme('resources/css/filament/admin/theme.css');
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                // UserMenuItem::make()
                //     ->label('Settings')
                //     ->url(fn (): string => PeriodeResource::getUrl(parameters: ['tenant' => Filament::getTenant()]))
                //     ->icon('heroicon-o-cog-6-tooth'),
            ]);
        });
    }


}
