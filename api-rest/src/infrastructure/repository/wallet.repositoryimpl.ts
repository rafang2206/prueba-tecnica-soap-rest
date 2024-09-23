import { WalletDatasource } from "../../domain/datasource/wallet.datasource";
import { WalletBalanceDto } from "../../domain/dtos/wallet-balance.dto";
import { WalletRechargeDto } from "../../domain/dtos/wallet-recharge.dto";
import { WalletRepository } from "../../domain/repository/wallet.repository";
import { DataResponse } from "../../types";


export class WalletRespositoryImpl implements WalletRepository {
  constructor(private readonly walletDatasource: WalletDatasource){}
  rechargeBalance(walletRechargeDto: WalletRechargeDto): Promise<DataResponse> {
    return this.walletDatasource.rechargeBalance(walletRechargeDto);
  }
  getBalance(walletBalanceDto: WalletBalanceDto): Promise<DataResponse> {
    return this.walletDatasource.getBalance(walletBalanceDto);
  }
  
}