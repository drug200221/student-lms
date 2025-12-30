export interface IMenuItem {
  id: number;
  title: string;
  icon?: string;
  children?: IMenuItem[] | null;
  route?: string;
}
