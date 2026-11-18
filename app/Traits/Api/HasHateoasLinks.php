<?php

namespace App\Traits\Api;

use Illuminate\Support\Facades\Auth;

/**
 * Trait HasHateoasLinks
 *
 * Provides HATEOAS (Hypermedia as the Engine of Application State) support for API resources.
 * Implements REST Level 3 - adds hypermedia links to resources.
 */
trait HasHateoasLinks
{
    /**
     * Build HATEOAS links for a resource
     *
     * @param  string  $resourceUrl  Base URL for the resource (e.g., '/api/v1/storage-areas')
     * @param  string  $resourceId  Unique identifier for the resource
     * @param  array  $actions  Available actions ['show', 'update', 'delete', 'custom' => [...]]
     * @param  array  $additionalLinks  Additional custom links
     */
    protected function buildHateoasLinks(
        string $resourceUrl,
        string $resourceId,
        array $actions = ['show', 'update', 'delete'],
        array $additionalLinks = []
    ): array {
        $links = [];
        $fullUrl = url($resourceUrl);
        $resourceUri = "{$fullUrl}/{$resourceId}";

        // Standard REST actions
        $standardActions = [
            'self' => ['method' => 'GET', 'href' => $resourceUri],
            'show' => ['method' => 'GET', 'href' => $resourceUri],
            'update' => ['method' => 'PUT', 'href' => $resourceUri],
            'patch' => ['method' => 'PATCH', 'href' => $resourceUri],
            'delete' => ['method' => 'DELETE', 'href' => $resourceUri],
        ];

        // Always include 'self' link
        if (! in_array('self', $actions)) {
            $links['self'] = $standardActions['self'];
        }

        // Add requested actions
        foreach ($actions as $key => $action) {
            if (is_array($action)) {
                // Custom action with configuration
                $links[$key] = $action;
            } elseif (isset($standardActions[$action])) {
                // Standard action
                $links[$action] = $standardActions[$action];
            }
        }

        // Merge additional links
        return array_merge($links, $additionalLinks);
    }

    /**
     * Build HATEOAS links with permission checking
     *
     * @param  array  $permissionMap  Map of action => permission name
     */
    protected function buildConditionalHateoasLinks(
        string $resourceUrl,
        string $resourceId,
        array $permissionMap = [],
        array $additionalLinks = []
    ): array {
        $links = [];
        $fullUrl = url($resourceUrl);
        $resourceUri = "{$fullUrl}/{$resourceId}";

        // Always include self link
        $links['self'] = [
            'href' => $resourceUri,
            'method' => 'GET',
        ];

        // Default permission map
        $defaultPermissions = [
            'update' => 'update',
            'delete' => 'delete',
        ];

        $permissions = array_merge($defaultPermissions, $permissionMap);

        // Check permissions and add links
        foreach ($permissions as $action => $permission) {
            if ($this->userCan($permission)) {
                $method = match ($action) {
                    'update' => 'PUT',
                    'patch' => 'PATCH',
                    'delete' => 'DELETE',
                    default => 'POST',
                };

                $links[$action] = [
                    'href' => $resourceUri,
                    'method' => $method,
                ];
            }
        }

        return array_merge($links, $additionalLinks);
    }

    /**
     * Check if user has permission
     */
    protected function userCan(string $permission): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Check if user has the permission
        return $user->can($permission);
    }

    /**
     * Build pagination links for HATEOAS
     */
    protected function buildPaginationLinks(
        string $baseUrl,
        int $currentPage,
        int $lastPage,
        array $queryParams = []
    ): array {
        $links = [];
        $fullUrl = url($baseUrl);

        // Self link
        $links['self'] = $this->buildUrlWithParams($fullUrl, array_merge($queryParams, ['page' => $currentPage]));

        // First page link
        $links['first'] = $this->buildUrlWithParams($fullUrl, array_merge($queryParams, ['page' => 1]));

        // Previous page link
        if ($currentPage > 1) {
            $links['prev'] = $this->buildUrlWithParams($fullUrl, array_merge($queryParams, ['page' => $currentPage - 1]));
        }

        // Next page link
        if ($currentPage < $lastPage) {
            $links['next'] = $this->buildUrlWithParams($fullUrl, array_merge($queryParams, ['page' => $currentPage + 1]));
        }

        // Last page link
        $links['last'] = $this->buildUrlWithParams($fullUrl, array_merge($queryParams, ['page' => $lastPage]));

        return $links;
    }

    /**
     * Build URL with query parameters
     */
    private function buildUrlWithParams(string $baseUrl, array $params): string
    {
        $queryString = http_build_query($params);

        return $queryString ? "{$baseUrl}?{$queryString}" : $baseUrl;
    }

    /**
     * Build collection links for HATEOAS
     */
    protected function buildCollectionLinks(
        string $resourceUrl,
        array $additionalActions = []
    ): array {
        $fullUrl = url($resourceUrl);

        $links = [
            'self' => [
                'href' => $fullUrl,
                'method' => 'GET',
            ],
            'create' => [
                'href' => $fullUrl,
                'method' => 'POST',
            ],
        ];

        return array_merge($links, $additionalActions);
    }
}
