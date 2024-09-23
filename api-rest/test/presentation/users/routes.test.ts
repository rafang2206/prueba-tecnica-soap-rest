import { describe, expect, test, jest, beforeEach } from '@jest/globals';
import request from "supertest";
import { testServer } from "../../server-test";
import { UserRepositoryImpl } from '../../../src/infrastructure/repository/user.repositoryimpl';
import { DataResponse } from '../../../src/types';

jest.mock('../../../src/infrastructure/repository/user.repositoryimpl');

const createMethodMock = jest
  .spyOn(UserRepositoryImpl.prototype, 'create')

describe("Users Routes Testing", () => {

  beforeEach(() => {
    jest.clearAllMocks();
  })

  test("Register User Successfully /api/user/register", async () => {

    const resultMock: DataResponse = {
      success: true,
      message: 'User created successfully',
    }

    createMethodMock.mockImplementation(() =>
			Promise.resolve(resultMock)
		)

    const userDto = {
      document: "C1943300",
      name: "Rafael",
      email: "soliditydevpro@gmail.com",
      phone: 3237419189
    };

    const response = await request(testServer.app)
      .post("/api/user/register")
      .send(userDto)
      .expect(200)
    console.log(response.body);
    expect(response.body.success).toBe(true);
    expect(response.body.message).toBe('User created successfully');
  })

  test("Register User Failed with code 400 Bad Request /api/user/register", async () => {

    const userDto = {
      name: "Rafael",
      email: "soliditydevpro@gmail.com",
      phone: 3237419189
    };

    const response = await request(testServer.app)
      .post("/api/user/register")
      .send(userDto)
      .expect(400)

    expect(response.body.success).toBe(false);
    expect(response.body.message_error).toBe('"document" is required');
    expect(response.body.cod_error).toBe(400);
  })

  test("Register User Failed document already registered /api/user/register", async () => {

    const resultMock: DataResponse = {
      success: false,
      message_error: 'the document is already registered',
      cod_error: 400
    }

    createMethodMock.mockImplementation(() =>
			Promise.resolve(resultMock)
		)

    const userDto = {
      document: "C1943300",
      name: "Rafael",
      email: "soliditydevpro@gmail.com",
      phone: 3237419189
    };

    const response = await request(testServer.app)
      .post("/api/user/register")
      .send(userDto)
      .expect(400)

    expect(response.body.success).toBe(false);
    expect(response.body.message_error).toBe('the document is already registered');
    expect(response.body.cod_error).toBe(400);
  })
})