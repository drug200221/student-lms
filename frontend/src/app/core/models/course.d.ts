import { ICourseContent } from './content';

export enum CourseType {
  STUD = 1,
  CDPO = 2,
  AUTO = 3
}

export interface ICourse {
  id: number;
  title: string;
  description: string | null;
  baseId: number;
  type: CourseType;
  createdAt: string;
  fillProgress: number;
  contents: ICourseContent[];
}

export namespace CourseDto {
  export interface CreateRequest {
    title: string;
    description?: string;
    baseId: number;
    type: CourseType;
  }

  export interface UpdateRequest extends Partial<CreateRequest> {
    id: number;
  }

  export interface FilterParams {
    type?: CourseType;
    search?: string;
  }
}
