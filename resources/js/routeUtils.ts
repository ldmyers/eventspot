/**
 * General functions for routes.
 *
 * Usage: import { cleanRoute } from './routeUtils';
 */

// Clean a string to be used as a route name.
export function cleanRoute(route: string): string {
    // Lowercase, replace spaces with dashes.
    let cleanedRoute = route.toLowerCase().split(' ').join('-');

    // Remove accents and special characters.
    cleanedRoute = cleanedRoute.normalize("NFD").replace(/[\u0300-\u036f]/g, '');

    // Remove all characters except a-z, 0-9 and dashes.
    cleanedRoute = cleanedRoute.replace(/[^a-z0-9-]/g, '');

    return cleanedRoute;
}
