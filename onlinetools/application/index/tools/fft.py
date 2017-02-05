# -*- coding: utf-8 -*-
import cv2
import numpy as np
import sys
import os
def savefft(imgpath, imgname):
	img = cv2.imread(imgpath + imgname, cv2.IMREAD_GRAYSCALE)
	img2 = cv2.dft(img.astype(np.float32), flags=cv2.DFT_COMPLEX_OUTPUT)
	dft_shift = np.fft.fftshift(img2)
	magnitude_spectrum = 20*np.log(cv2.magnitude(dft_shift[:,:,0],dft_shift[:,:,1]))
	basename = os.path.splitext(imgname)[0]
	cv2.imwrite(imgpath + basename + '_fft.png', magnitude_spectrum, [cv2.IMWRITE_PNG_COMPRESSION, 9])
	cv2.imwrite(imgpath + basename + '.png', img, [cv2.IMWRITE_PNG_COMPRESSION, 9])
	print('successful')
	print(basename + '.png')
	print(basename + '_fft.png')

if __name__ == '__main__':
	try:
		imgpath = sys.argv[1]
		imgname = sys.argv[2]
		savefft(imgpath, imgname)
	except:
		print("failed")