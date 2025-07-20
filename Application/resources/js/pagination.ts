export const DEFAULT_PAGINATION_META = {
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
} as const;

export type PaginationMeta = {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
};
