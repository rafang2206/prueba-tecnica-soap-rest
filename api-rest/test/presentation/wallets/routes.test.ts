import { describe, expect, test, jest, beforeEach, afterEach } from '@jest/globals';
import request from "supertest";
import { testServer } from "../../server-test";
import { WalletRespositoryImpl } from '../../../src/infrastructure/repository/wallet.repositoryimpl';
import { WalletBalanceDto } from '../../../src/domain/dtos/wallet-balance.dto';
import { DataResponse } from '../../../src/types';

jest.mock('../../../src/infrastructure/repository/wallet.repositoryimpl');

const getBalanceMethodMock = jest.spyOn(
  WalletRespositoryImpl.prototype, 'getBalance' 
)

describe("Wallets Routes Test", () => {

  beforeEach(async () => {
    await testServer.close();
    await testServer.start();
    jest.clearAllMocks();
  })

  afterEach(async () => {
    await testServer.close();
    jest.clearAllMocks();
  })

  test("Should Be return value of a Wallet with 10 dollars of Balance /api/wallet/balance", async () => {

    const resultMock: DataResponse = {
      success: true,
      data: {
        balance: 10
      },
    }

    getBalanceMethodMock.mockImplementation(() =>
			Promise.resolve(resultMock)
		)

    const queryParams: WalletBalanceDto = {
      document: 'D-1999',
      phone: 9999393,
    }
    
    const response = await request(testServer.app)
      .get('/api/wallet/balance')
      .query(queryParams)
      .expect(200)

    expect(response.body.success).toBeTruthy();
    expect(response.body.data).toEqual(expect.objectContaining({
      balance: 10
    }))

  })

  test("Should Be return Bad Request 400 Document its required /api/wallet/balance", async () => {

    const response = await request(testServer.app)
      .get('/api/wallet/balance')
      .query({ phone: 9999393 })
      .expect(400)

    expect(response.body.success).toBeFalsy();
    expect(response.body.message_error).toBe('"document" is required');
    expect(response.body.cod_error).toBe(400);

  })


  test("Should Be return Bad Request 400 Phone its required /api/wallet/balance", async () => {

    const response = await request(testServer.app)
      .get('/api/wallet/balance')
      .query({ document: 'D-1999' })
      .expect(400)

    expect(response.body.success).toBeFalsy();
    expect(response.body.message_error).toBe('"phone" is required');
    expect(response.body.cod_error).toBe(400);

  })
  
  test("Should Be return User no Exist  /api/wallet/balance", async () => {

    const resultMock: DataResponse = {
      success: false,
      message_error: "the User Dont's Exist",
      cod_error: 400
    }

    getBalanceMethodMock.mockImplementation(() =>
			Promise.resolve(resultMock)
		)

    const queryParams: WalletBalanceDto = {
      document: 'D-1999',
      phone: 9999393,
    }

    const response = await request(testServer.app)
      .get('/api/wallet/balance')
      .query(queryParams)
      .expect(400)

    expect(response.body.success).toBeFalsy();
    expect(response.body.message_error).toBe("the User Dont's Exist");
    expect(response.body.cod_error).toBe(400);

  })

  test("Should Be return Incorrect user phone number  /api/wallet/balance", async () => {

    const resultMock: DataResponse = {
      success: false,
      message_error: "Incorrect user phone number",
      cod_error: 400
    }

    getBalanceMethodMock.mockImplementation(() =>
			Promise.resolve(resultMock)
		)

    const queryParams: WalletBalanceDto = {
      document: 'D-1999',
      phone: 9999393,
    }

    const response = await request(testServer.app)
      .get('/api/wallet/balance')
      .query(queryParams)
      .expect(400)

    expect(response.body.success).toBeFalsy();
    expect(response.body.message_error).toBe("Incorrect user phone number");
    expect(response.body.cod_error).toBe(400);

  })
})