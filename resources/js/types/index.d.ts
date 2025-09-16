import type { Config } from 'ziggy-js';


export interface ModelI {
    id: number;
    uuid: string;
    createdBy?: User;
    created_by?: number;
    created_at: string;
    updated_at: string;
    created_at_str: string;
}

export interface Auth {
    user: User;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    announcement: AnnouncementI|null;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    success: boolean,
    message: string,
    data: {
        [key]: string
    }
};

export interface Subject extends ModelI {
    name: string
    code: string
    slug: string
    classes_count: number
    students_count: number
}

export interface StaffI extends ModelI {
    school_id: number
    title?: number | null;
    classroom: Classroom
    parents: GuardianI[]
    staff_id?: number | null;
    email?: string | null;
    phone?: string | null;
    gender?: string | null;
    dob?: string | null;

    first_name: string ;
    middle_name?: string | null;
    last_name: string ;
    fullname: string


    designation: string
    national_id: string
    marital_status: string
    religion?: string | null;
    region?: string | null;
    city?: string | null;
    bio?: string | null;
    licence_no: string
    qualification: string
    experience: string
    basic_salary: string


    fingerprint_hash?: string | null;
    suspended_reason?: string | null;

    status?: string | null;
    password: string;
    image: string

}
export interface StudentI extends ModelI {
    school_id: number
    previous_school_id?: number | null;
    class_id: number;
    classroom: Classroom
    parents: GuardianI[]
    transportation_id?: number | null;

    first_name: string ;
    middle_name?: string | null;
    last_name: string ;
    fullname: string
    index_number: string;

    email?: string | null;
    phone?: string | null;
    gender?: string | null;
    dob?: string | null;
    religion?: string | null;
    region?: string | null;
    city?: string | null;
    bio?: string | null;
    allergies?: string | null;

    fingerprint_hash?: string | null;
    suspended: boolean;
    suspended_reason?: string | null;

    status?: string | null;
    password: string;
    image: string

}

export interface GuardianI extends ModelI {
    name: string
    email: string
    phone: string
    identity_number: string
    address: string
    occupation: string
}

export interface Semester extends ModelI {
    name: string
    status: string
    start_date: string
    end_date: string
    days: number
    next_term_begins: string
}

export interface Classroom extends ModelI {
    school_id: number
    name: string
    level: string
    group: string
    slug: string
    students_count: number

}

export interface User extends ModelI {
    firstname: string;
    lastname: string;
    fullname: string;
    initials: string;
    mobile: string;
    email: string;
    email_verified_at: string | null;
    password: string;

}







export interface MediaI {
    'id': number,
    'model_type': string,
    'model_id': number,
    'uuid': string,
    'collection_name': string,
    'name': string,
    'file_name': string,
    'mime_type': string,
    'size': number,
    'generated_conversions': [],
    'order_column': 1,
    'created_at': string,
    'updated_at': string,
    'url': string,
    'original_url': string,
}




export interface RegionI extends ModelI {
    name: string;
    slug: string;
    country: CountryI,
}


export interface PaginationLinkI {
    active: boolean;
    label: string;
    url?: string | null;
}

export interface PaginatedDataI<DT> {
    current_page: number,
    data?: DT[],
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    links: PaginationLinkI[],
    next_page_url: string,
    path: string,
    per_page: number,
    prev_page_url: string,
    to: number,
    total: number,
}



export type InputSelectOption = {
    label: string
    value: number|string
}
