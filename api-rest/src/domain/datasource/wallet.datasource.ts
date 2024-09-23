import { DataResponse } from "../../types";
import { WalletBalanceDto } from "../dtos/wallet-balance.dto";
import { WalletRechargeDto } from "../dtos/wallet-recharge.dto";
import { Wallet } from "../entities/wallet";

export abstract class WalletDatasource {
  abstract getBalance(walletBalanceDto: WalletBalanceDto): Promise<DataResponse>;
  abstract rechargeBalance(walletRechargeDto: WalletRechargeDto): Promise<DataResponse>;
}