import { DataResponse } from "../../types";
import { WalletBalanceDto } from "../dtos/wallet-balance.dto";
import { WalletRechargeDto } from "../dtos/wallet-recharge.dto";

export abstract class WalletRepository {
  abstract getBalance(walletBalanceDto: WalletBalanceDto): Promise<DataResponse>;
  abstract rechargeBalance(walletRechargeDto: WalletRechargeDto): Promise<DataResponse>;
}