import { DataResponse } from "../../types";
import { CreateUserDto } from "../dtos/create-user.dto";

export abstract class UserDatasource {
  abstract create(user: CreateUserDto): Promise<DataResponse>;
}