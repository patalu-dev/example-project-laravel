export interface User {
    id: string;
    name: string;
    username: string;
    email: string;
    password: string;
    is_active: boolean;
}

export interface Permission {
    id: string;
    name: string;
    guard_name: string;
    roles: Role[];
}

export interface Role {
    id: string;
    name: string;
    guard_name: string;
}
