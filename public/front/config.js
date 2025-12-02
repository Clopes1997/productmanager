// API Configuration
// This file allows you to configure the API base URL for different environments
// For GitHub Pages, set this to your deployed Laravel API URL
// For local development, leave it empty to use relative URLs

const API_CONFIG = {
    // Set this to your API base URL (e.g., 'https://your-api-domain.com')
    // Leave empty string '' to use relative URLs (for same-domain deployment)
    baseURL: window.API_BASE_URL || ''
};

// Helper function to get the full API URL
function getApiUrl(endpoint) {
    // Remove leading slash from endpoint if present
    const cleanEndpoint = endpoint.startsWith('/') ? endpoint.substring(1) : endpoint;
    
    if (API_CONFIG.baseURL) {
        // Ensure baseURL doesn't end with a slash
        const base = API_CONFIG.baseURL.endsWith('/') 
            ? API_CONFIG.baseURL.slice(0, -1) 
            : API_CONFIG.baseURL;
        return `${base}/${cleanEndpoint}`;
    }
    
    // Use relative URL (for same-domain deployment)
    return `/${cleanEndpoint}`;
}

