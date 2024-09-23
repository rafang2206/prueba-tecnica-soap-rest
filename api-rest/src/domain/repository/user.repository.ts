import { DataResponse } from "../../types";
import { CreateUserDto } from "../dtos/create-user.dto";

export abstract class UserRepository {
  abstract create(user: CreateUserDto): Promise<DataResponse>;
}