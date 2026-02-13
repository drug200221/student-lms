import { IConfiguration } from './configuration';

export interface ITest {
  id: number | null,
  courseId: number,
  title: string,
  timeLimit: number | null,
  startDate: string,
  endDate: string,
  status: string,
  configuration: IConfiguration,
}
