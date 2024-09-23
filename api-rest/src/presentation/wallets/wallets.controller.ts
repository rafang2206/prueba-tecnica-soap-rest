import { Request, Response } from "express";
import { HTTP_STATUS } from "../../constants/httpStatus";
import { WalletRepository } from "../../domain/repository/wallet.repository";
import { WalletBalanceDto } from "../../domain/dtos/wallet-balance.dto";
import { WalletRechargeDto } from "../../domain/dtos/wallet-recharge.dto";
import { logger } from "../../config/logger";

export class WalletController {

  constructor(
    private readonly walletRepository: WalletRepository,
  ) { }

  public rechargeBalance = async (req: Request, res: Response) => {
    const { document, phone, amount } = req.body!;
    logger.info('Proccesing Recharge Balance');
    const walletRechargeDto = new WalletRechargeDto({ 
      document: String(document), 
      phone: +phone!, 
      amount: +amount! 
    });
    const result = await this.walletRepository.rechargeBalance(walletRechargeDto);
    if(!result.success){
      logger.error('Error Proccesing Recharge Balance', {
        "controller": "wallets.controller"
      });
      return res
      .status(result?.cod_error || HTTP_STATUS.INTERNAL_SERVER_ERROR)
      .json(result)
    }
    logger.info("Recharge proccesing successfully");
    return res
      .status(HTTP_STATUS.OK)
      .json(result)
  }

  public getBalance = async (req: Request, res: Response) => {
    const { document, phone } = req.query!;
    logger.info('Obtained Balance of Wallet');
    const walletBalanceDto = new WalletBalanceDto({ document: String(document), phone: +phone! });
    const result = await this.walletRepository.getBalance(walletBalanceDto);
    if(!result.success){
      logger.error('Error Obtained Balance of Wallet', {
        "controller": "wallet.controller"
      });
      return res
      .status(result?.cod_error || HTTP_STATUS.INTERNAL_SERVER_ERROR)
      .json(result)
    }
    logger.info("Balance of Wallet Get Successfully");
    return res
      .status(HTTP_STATUS.OK)
      .json(result)
  }
}