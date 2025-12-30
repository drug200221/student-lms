export enum ContentType {
  Type1 = 1,
  Type2 = 2,
  Type3 = 3
}

export interface ICourseContent {
  id: number;
  courseId: number;
  parentId: number;
  title: string;
  content: string | null;
  path: string | null;
  revision: number;

  /** @deprecated */
  type: ContentType;

  createdAt: string;
  updatedAt: string | null;

  treeLevel: number;
  treeLeft: number;
  treeRight: number;
  treeOrder: number;

  children: ICourseContent[];
}

export namespace ContentDto {

  export interface CreateRequest {
    courseId: number;
    parentId: number;
    title: string;
    type?: ContentType;
  }

  export interface MoveRequest {
    id: number;
    targetParentId: number;
    newOrder: number;
  }

  export interface UpdateRequest {
    id: number;
    title?: string;
    content?: string;
    path?: string;
  }
}
