<?php

namespace App\Enums;

enum Permission: string
{
    // Dashboard
    case AccessDashboard    = 'access dashboard';
    case ViewAnalytics      = 'view analytics';

    // Posts
    case ViewPosts          = 'view posts';
    case CreatePosts        = 'create posts';
    case EditPosts          = 'edit posts';
    case EditAnyPost        = 'edit any post';      // Other users' posts
    case DeletePosts        = 'delete posts';
    case DeleteAnyPost      = 'delete any post';    // Other users' posts
    case PublishPosts       = 'publish posts';
    case FeaturePosts       = 'feature posts';

    // Categories
    case ViewCategories     = 'view categories';
    case CreateCategories   = 'create categories';
    case EditCategories     = 'edit categories';
    case DeleteCategories   = 'delete categories';

    // Tags
    case ViewTags           = 'view tags';
    case CreateTags         = 'create tags';
    case DeleteTags         = 'delete tags';

    // Users
    case ViewUsers          = 'view users';
    case CreateUsers        = 'create users';
    case EditUsers          = 'edit users';
    case DeleteUsers        = 'delete users';

    // Roles
    case ViewRoles          = 'view roles';
    case CreateRoles        = 'create roles';
    case EditRoles          = 'edit roles';
    case DeleteRoles        = 'delete roles';
    case ManageRoles        = 'manage roles';

    // Comments
    case ViewComments       = 'view comments';
    case ApproveComments    = 'approve comments';
    case DeleteComments     = 'delete comments';

    // Media
    case UploadMedia        = 'upload media';
    case DeleteMedia        = 'delete media';

    // Settings
    case ViewSettings       = 'view settings';
    case EditSettings       = 'edit settings';

    //Maintenance
    case BypassMaintenance  = 'bypass-maintenance';

    // Reports / Analytics
    case ViewReports        = 'view reports';
    case ExportReports      = 'export reports';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
        //👉 எல்லா enum values-யும் array-ஆ return பண்ணும்
    }

    public static function grouped(): array
    {
        return [
            'Dashboard'  => [self::AccessDashboard, self::ViewAnalytics],
            'Posts'      => [
                self::ViewPosts, self::CreatePosts,
                self::EditPosts, self::EditAnyPost,
                self::DeletePosts, self::DeleteAnyPost,
                self::PublishPosts, self::FeaturePosts,
            ],
            'Categories' => [
                self::ViewCategories, self::CreateCategories,
                self::EditCategories, self::DeleteCategories,
            ],
            'Tags'       => [self::ViewTags, self::CreateTags, self::DeleteTags],
            'Users'      => [
                self::ViewUsers, self::CreateUsers,
                self::EditUsers, self::DeleteUsers,
            ],
            'Roles'      => [
                self::ViewRoles, self::CreateRoles,
                self::EditRoles, self::DeleteRoles, self::ManageRoles,
            ],
            'Comments'   => [
                self::ViewComments, self::ApproveComments, self::DeleteComments,
            ],
            'Media'      => [self::UploadMedia, self::DeleteMedia],
            'Settings'   => [self::ViewSettings, self::EditSettings],
            'Maintenance'   => [self::BypassMaintenance],
            'Reports'    => [self::ViewReports, self::ExportReports],
        ];
    }
}
